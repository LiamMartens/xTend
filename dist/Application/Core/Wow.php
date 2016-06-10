<?php
	namespace xTend\Core;
	class Wow
	{
		private $_rx_version;
		private $_rx_layout;
		private $_rx_flag;
		private $_rx_section;
		private $_rx_section_extract;
		private $_rx_module;
		private $_rx_module_extract;

		private $_expressions;
		private $_app;
		public function __construct($app) {
			$this->_rx_version = $this->rx("(\s+|^)(@)(version)(:)([\d\.]+)(\s+|$)", "i");
			$this->_rx_layout = $this->rx("(\s+|^)(@)(layout)(:)([\w\-\_\.]+)(\s+|$)", "i");
			$this->_rx_flag = $this->rx("(\s+|^)(@)(compile)(:)(always|version|never|change|change\+version)(\s+|$)", "i");
			$this->_rx_section = $this->rx("(@section:[\w\-\_]+)", "i");
			$this->_rx_section_extract = $this->rx("(@startsection:%s)(.*)(@endsection:%s)", "si");
			$this->_rx_module = $this->rx("(@module:)([\w\-\_\.]+)", "i");
			$this->_rx_module_extract = $this->rx("(@module:[\w\-\_\.]+)", "i");

			$this->_expressions=[];
			$this->_app=$app;
		}
		public function rx($pattern, $flags) {
			return "/$pattern/$flags";
		}
		public function registerExpression($rx, $replacement) {
			$this->_expressions[$rx]=$replacement;
		}
		//compiler options
		private function version(&$content) {
			//must be at the beginning of a line
			$rx_matches=[]; preg_match($this->_rx_version, $content, $rx_matches);
			if(isset($rx_matches[5]))
				return floatval($rx_matches[5]);
			return false;
		}
		private function layout(&$content) {
			$rx_matches=[]; preg_match($this->_rx_layout, $content, $rx_matches);
			if(isset($rx_matches[5]))
				return $rx_matches[5];
			return false;
		}
		private function flag(&$content) {
			//compile flags -> always (always compile), version (compile on version change to higher one),
			//never (never compile), change (compile on content change) -> version stays the same so
			//beware for data loss in compiled views
			//change+version -> only compile when the version changes and the content changes, this prevents
			//data loss in the compiled outputs as you do need to manually change the version number
			$rx_matches=[]; preg_match($this->_rx_flag, $content, $rx_matches);
			if(isset($rx_matches[5]))
				return $rx_matches[5];
			return false;
		}
		//file changed
		private function changed($path, &$view_content, $layout=false) {
			//get last modification time
			$time_last_mod=filemtime($path);
			//get last compile times
			$last_compile = $path->getMeta("last_compile");
			//if last compile hasn't been saved -> return true
			if($last_compile==false) return true;
			//if the last compile time is smaller than the last compile time also return true
			if(floatval($last_compile)<$time_last_mod) return true;
			//check for all the modules
			$modules=[]; preg_match_all($this->_rx_module, $view_content, $modules);
			if(isset($modules[2])) {
				foreach ($modules[2] as $mod_name) {
					$file_path=$this->_app->getModulesDirectory()->file("$mod_name.wow.php", 2);
					if($file_path->exists()) {
						//module exists -> check the change time
						if(filemtime($file_path)>floatval($last_compile)) return true; } } }
			//check layout compile time
			if($layout!==false) {
				//a layout was passed and no value was returned yet -> check the layout change and the view compile time
				$time_layout_last_mod = filemtime($layout);
				if($time_layout_last_mod>floatval($last_compile)) return true;
			}
			//if all fails return false
			return false;
		}
		//update meta method
		private function update($path) {
			$path->setMeta("last_compile", time());
		}
		//compile part method
		private function compileRaw($content, $modules_dir = false) {
			foreach ($this->_expressions as $rx => $repl) {
				$content = preg_replace($rx, $repl, $content);
				$content = $this->compile($content, $modules_dir);
			}
			return $content;
		}
		private function compile($content, $modules_dir = false) {
			//remove @version, @compile, @layout and @section flags as these
			//should not be repaced by anything, they should be ignored
			$content = preg_replace($this->_rx_version, "", $content);
			$content = preg_replace($this->_rx_layout, "", $content);
			$content = preg_replace($this->_rx_flag, "", $content);
			$content = preg_replace($this->_rx_section, "", $content);
			//split into modules if any
			$final_content="";
			$mod_split = preg_split($this->_rx_module_extract, $content, NULL, PREG_SPLIT_DELIM_CAPTURE);
			foreach ($mod_split as $part) {
				if(!preg_match($this->_rx_module, $part)) {
					//it is not a module
					$part=$this->compileRaw($part, $modules_dir);
				} else {
					//get module contents
					$mod_name=substr($part, 8); $mod_name=substr($mod_name, 0, strlen($mod_name));
					$mod_path=($modules_dir===false) ?
									($this->_app->getModulesDirectory()->file("$mod_name.wow.php", 2)) :
									($modules_dir->file("$mod_name.wow.php", 2));
					if($mod_path->exists()) {
						$part=$this->compile($mod_path->read(), $modules_dir); } }
				$final_content.=$part; }
			$content=$final_content;
			return $final_content;
		}
		//compile layout method
		private function compileLayout($layout_c, $modules_dir = false) {
			//split the layout into sections
			$layout_c=$this->compileRaw($layout_c, $modules_dir);
			$split = preg_split($this->_rx_section, $layout_c, NULL, PREG_SPLIT_DELIM_CAPTURE);
			foreach ($split as &$part) {
				if(!preg_match($this->_rx_section, $part))
					$this->compile($part, $modules_dir);
			}
			//returns array of compiled parts and the section parts
			return $split;
		}
		//compile view method
		public function compileView($file, $layout_dir = false, $modules_dir = false) {
			//file hash
			$file_hash = hash("sha256", $file);
			//get file name 'name'
			$file_name=substr($file, strrpos($file, "/")+1); $file_name=substr($file_name, 0, strpos($file_name, "."));
			//get view content
			$view_c = $file->read();
			//get view flags
			$version=$this->version($view_c);
			$layout=$this->layout($view_c);
			$layout_path=false;
			$flag=$this->flag($view_c);
			//check for layout existance -> if it doesnt exist, ignore the layout, thus set it to false
			if($layout!==false) {
				$layout_path=($layout_dir===false) ?
									($this->_app->getLayoutsDirectory()->file("$layout.wow.php", 2)) :
									($layout_dir->file("$layout.wow.php", 2));
				if(!$layout_path->exists()) { $layout=false; $layout_path=false; } }
			//get last compiled version of this view file -> sorting works descending thus most recent versions are first
			$is_new_version = false; $one_found=false;
			$compiled_views = $this->_app->getViewOutputDirectory()->files(); rsort($compiled_views);
			//check for the current version in the array
			foreach ($compiled_views as $cv) {
				$pos=strpos($cv, ".v");
				//get version number
				$v_num = floatval(trim(substr($cv, $pos+2), ".php"));
				//get actual view name -> hash("sha256") of the view path
				$v_hash = substr($cv, 0, $pos);
				//check for hash compliance
				if($v_hash==$file_hash) {
					$one_found=true; if($v_num<$version) {
						$is_new_version=true; break; } } }
			//get whether the view has changed (either the view itself or the layout)
			$has_changed=$this->changed($file, $view_c, $layout_path);
			//check whether view needs to be compiled
			$compile_view=false;
			if($flag!==false) {
				//set to lowercase
				$flag=strtolower($flag);
				//check flag option
				if(($flag=="always")||
					(($flag!="never")&&(!$one_found))||
					(($flag=="version")&&($is_new_version))||
					(($flag=="change")&&($has_changed))||
					(($flag=="change+version")&&($has_changed)&&($is_new_version)))
					$compile_view=true;
			} else $compile_view=true;
			//view has to be compiled
			if($compile_view) {
				$compiled_string="";
				if($layout!==false) {
					//compile using a layout
					$layout_parts=$this->compileLayout($layout_path->read(), $modules_dir);
					foreach ($layout_parts as $part) {
						//check for section request
						$is_section=(preg_match($this->_rx_section, $part)==1) ? true : false;
						if($is_section) {
							$section_name=substr($part, 9);
							//got the section name, now take the section content out of the view content
							$rx = sprintf($this->_rx_section_extract, $section_name, $section_name);
							$rx_matches=[]; preg_match($rx, $view_c, $rx_matches);
							if(isset($rx_matches[2]))
								$compiled_string.=$this->compile($rx_matches[2], $modules_dir);
						} else { $compiled_string.=$part; }
					}
				} else { $compiled_string=$this->compile($file->read(), $modules_dir); }
				//add namespace to compiled_string
				$compiled_string="<?php namespace ".$this->_app->getNamespace()."; \$app=\\xTend\\Core\\getCurrentApp(__NAMESPACE__); ?>".$compiled_string;
				//write view output
				$this->_app->getViewOutputDirectory()->file("$file_hash.v$version.php", 2)->write($compiled_string);
				//update meta file
				$this->update($file);
			}
			//return compiled view filename
			return ($this->_app->getViewOutputDirectory()->file("$file_hash.v$version.php", 2));
		}
	}
