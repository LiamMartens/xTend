<?php
	namespace xTend
	{
		class Wow
		{
			private $_rx_version;
			private $_rx_layout;
			private $_rx_flag;
			private $_rx_section;
			private $_rx_section_extract;
			private $_rx_module;

			private $_expressions;
			private $_app;
			public function __construct($app) {
				$this->_rx_version = $this->rx("(\s+|^)(@)(version)(:)([\d\.]+)(\s+|$)", "i");
				$this->_rx_layout = $this->rx("(\s+|^)(@)(layout)(:)([\w\-\_\.]+)(\s+|$)", "i");
				$this->_rx_flag = $this->rx("(\s+|^)(@)(compile)(:)(always|version|never|change|change\+version)(\s+|$)", "i");
				$this->_rx_section = $this->rx("(@section:[\w\-\_]+)", "i");
				$this->_rx_section_extract = $this->rx("(@startsection:%s)((\s|.)*)(@endsection:%s)", "i");
				$this->_rx_module = $this->rx("(@module\()(.+)(\))", "i");

				$this->_expressions=[];
				$this->_app=$app;

				/**
					add modules rx -> modules are not pre compiled parts, they are dynamically inserted
				**/
				$this->registerExpression($this->_rx_module, "<?php echo \\xTend\\getCurrentApp(__DIR__)->getWowCompiler()->module(\"$2\"); ?>");
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
			private function changed($path, $layout=false) {
				//get last modification time
				$time_last_mod=filemtime($path);
				//get last compile times
				$last_compile = $this->_app->getFileHandler()->getFileMeta($path, "last_compile");
				//if last compile hasn't been saved -> return true
				if($last_compile==false) return true;
				//if the last compile time is smaller than the last compile time also return true
				if(floatval($last_compile)<$time_last_mod) return true;
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
				$this->_app->getFileHandler()->setFileMeta($path, "last_compile", time());
			}
			//compile part method
			private function compile(&$content) {
				//remove @version, @compile, @layout and @section flags as these
				//should not be repaced by anything, they should be ignored
				$content = preg_replace($this->_rx_version, "", $content);
				$content = preg_replace($this->_rx_layout, "", $content);
				$content = preg_replace($this->_rx_flag, "", $content);
				$content = preg_replace($this->_rx_section, "", $content);
				//check every registered expression
				foreach ($this->_expressions as $rx => $repl) {
					$content = preg_replace($rx, $repl, $content); }
				return $content;
			}
			//compile layout method
			private function compileLayout(&$layout_c) {
				//split the layout into sections
				$split = preg_split($this->_rx_section, $layout_c, NULL, PREG_SPLIT_DELIM_CAPTURE);
				foreach ($split as &$part) {
					if(!preg_match($this->_rx_section, $part))
						$this->compile($part);
				}
				//returns array of compiled parts and the section parts
				return $split;
			}
			//compile view method
			public function compileView($file) {
				//file hash
				$file_hash = hash("sha256", $file);
				//get file name 'name'
				$file_name=substr($file, strrpos($file, "\\")+1); $file_name=substr($file_name, 0, strpos($file_name, "."));
				//get view content
				$view_c = $this->_app->getFileHandler()->read($file);
				//get view flags
				$version=$this->version($view_c);
				$layout=$this->layout($view_c);
				$layout_path=false;
				$flag=$this->flag($view_c);
				//check for layout existance -> if it doesnt exist, ignore the layout, thus set it to false
				if($layout!==false) {
					$layout_path=$this->_app->getFileHandler()->systemFile("Layouts.$layout.wow").".php";
					if(!$this->_app->getFileHandler()->exists($layout_path)) { $layout=false; $layout_path=false; } }
				//get last compiled version of this view file -> sorting works descending thus most recent versions are first
				$is_new_version = false; $one_found=false;
				$compiled_views = $this->_app->getDirectoryHandler()->files($this->_app->getDirectoryHandler()->systemDirectory("ViewOutput")); rsort($compiled_views);
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
				$has_changed=$this->changed($file, $layout_path);
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
						$layout_parts=$this->compileLayout($this->_app->getFileHandler()->read($layout_path));
						foreach ($layout_parts as $part) {
							//check for section request
							$is_section=(preg_match($this->_rx_section, $part)==1) ? true : false;
							if($is_section) {
								$section_name=substr($part, 9);
								//got the section name, now take the section content out of the view content
								$rx = sprintf($this->_rx_section_extract, $section_name, $section_name);
								$rx_matches=[]; preg_match($rx, $view_c, $rx_matches);
								if(isset($rx_matches[2]))
									$compiled_string.=$this->compile($rx_matches[2]);
							} else { $compiled_string.=$part; }
						}
					} else { $compiled_string=$this->compile($this->_app->getFileHandler()->read($file)); }
					//write view output
					$this->_app->getFileHandler()->write($this->_app->getFileHandler()->systemFile("ViewOutput.$file_hash.v")."$version.php", $compiled_string);
					//update meta file
					$this->update($file);
				}
				//return compiled view filename
				return ($this->_app->getFileHandler()->systemFile("ViewOutput.$file_hash.v")."$version.php");
			}
			//module inclusion
			public function module($mod_name) {
				$file_path=$this->_app->getFileHandler()->systemFile("Modules.$mod_name.wow").".php";
				if($this->_app->getFileHandler()->exists($file_path)) {
					$content = $this->_app->getFileHandler()->read($file_path);
					return $this->compile($content);
				}
				return false;
			}
		}
	}