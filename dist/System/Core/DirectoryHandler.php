<?php
	namespace xTend\Core\DirectoryHandler {
		class Directory {
			private $_dirHandler;
			private $_path;
			public function __construct($app, $path) {
				$this->_dirHandler = $app->getDirectoryHandler();
				$this->_path = $path;
			}
			public function __call($name, $args) {
				array_unshift($args, $this->_path);
				return call_user_func_array([ $this->_dirHandler, $name ], $args);
			}
			public function __toString() {
				return $this->_path;
			}
		}
	}
	namespace xTend\Core {
		class DirectoryHandler
		{
			/**
				Consistent / instead of \
				/.[!.][!.]* includes all hidden files but exclude entries such as . and ..
			**/
			private $_app;
			public function __construct($app) {
				//store containing app reference so the DirectoryHandler can use it's directives
				$this->_app = $app;
				$this->_app->getErrorCodeHandler()->registerErrorCode(0x0006, "directoryhandler:directory-not-writable", "A directory you are tryig to write to is not writable");
			}
			private function fromPathToObject($path) {
				if($this->exists($path)&&is_string($path)) {
					return new DirectoryHandler\Directory($this->_app, $path);
				} else if($this->_app->getFileHandler()->exists($path)&&is_string($path)) {
					return new FileHandler\File($this->_app, $path); }
				return $path;
			}
			private function fromPathArrayToObjects($pathArray) {
				return array_map([ $this, 'fromPathToObject' ], $pathArray);
			}
			//internal writable check
			private function writableCheck($path) {
				$path=str_replace("\\", "/", $path);
				if(!is_writable($path)&&is_dir($path)) {
					throw $this->_app->getErrorCodeHandler()->findError(0x0006)->getException();
					die($path);
				} else {
					$path = substr($path, 0, strrpos($path, "/"));
					if(!is_writable($path)&&is_dir($path)) {
						throw $this->_app->getErrorCodeHandler()->findError(0x0006)->getException();
						die($path); }
				}
			}
			public function isWritable($path) {
				return (is_writable($path)&&is_dir($path));
			}
			public function systemDirectory($dirName) {
				$path=$this->_app->getSystemDirectory();
				$dir_parts = explode(".", $dirName);
				//foreach loop is possible here
				foreach ($dir_parts as $part) { $path.="/".$part; }
				return new DirectoryHandler\Directory($this->_app, $path);
			}
			public function publicDirectory($dirName) {
				$path=$this->_app->getPublicDirectory();
				$dir_parts = explode(".", $dirName);
				//foreach loop is possible here
				foreach ($dir_parts as $part) { $path.="/".$part; }
				return new DirectoryHandler\Directory($this->_app, $path);
			}
			public function exists($path) {
				return is_dir($path);
			}
			//helper function to get the last part of the path
			private function getName($path) {
				//replace any \\ with /
				$path=str_replace("\\", "/", $path);
				//get postion of last /
				$pos_back = strrpos($path, "/");
				//return full path if there was no \
				return ($pos_back===false) ? $path : substr($path, $pos_back+1);
			}
			//directory scan
			private function scanIncudingDirectory($path) {
				//no directory exists checking here as they are only used in scan and shouldn't be called separately
				//replace \\ with /
				$path=str_replace("\\", "/", $path);
				return $this->fromPathArrayToObjects(array_merge(glob("$path/*"), glob("$path/.[!.][!.]*")));
			}
			private function scanExcludingDirectory($path) {
				//no directory exists checking here as they are only used in scan and shouldn't be called separately
				//replace \\ with /
				$path=str_replace("\\", "/", $path);
				$contents = array_merge(glob("$path/*"), glob("$path/.[!.][!.]*")); $entries=[];
				foreach ($contents as $entry) { $entries[]=$this->getName($entry); }
				return $entries;
			}
			public function scan($path, $include_directory = false) {
				//returns both files and directories
				if(!$this->exists($path))
					return false;
				if($include_directory)
					return $this->scanIncudingDirectory($path);
				else return $this->scanExcludingDirectory($path);
			}
			//directory files
			private function filesIncludingDirectory($path) {
				//replace \\ with /
				$path=str_replace("\\", "/", $path);
				$contents = array_merge(glob($path."/*"), glob($path."/.[!.][!.]*")); $entries=[];
				foreach ($contents as $entry) { if(is_file($entry)) $entries[] = $entry; }
				return $this->fromPathArrayToObjects($entries);
			}
			private function filesExcludingDirectory($path) {
				//replace \\ with /
				$path=str_replace("\\", "/", $path);
				$contents = array_merge(glob($path."/*"), glob($path."/.[!.][!.]*"));; $entries=[];
				foreach ($contents as $entry) { if(is_file($entry)) $entries[] = $this->getName($entry); }
				return $entries;
			}
			public function files($path, $include_directory = false) {
				//returns only files
				if(!$this->exists($path))
					return false;
				if($include_directory)
					return $this->filesIncludingDirectory($path);
				else return $this->filesExcludingDirectory($path);
			}
			//directory dirs
			private function directoriesIncludingDirectory($path) {
				//replace \\ with /
				$path=str_replace("\\", "/", $path);
				return $this->fromPathArrayToObjects(array_merge(glob($path."/*", GLOB_ONLYDIR), glob($path."/.[!.][!.]*", GLOB_ONLYDIR)));
			}
			private function directoriesExcludingDirectory($path) {
				//replace \\ with /
				$path=str_replace("\\", "/", $path);
				$contents = array_merge(glob($path."/*", GLOB_ONLYDIR), glob($path."/.[!.][!.]*", GLOB_ONLYDIR)); $entries=[];
				foreach ($contents as $entry) { $entries[] = $this->getName($entry); }
				return $entries;
			}
			public function directories($path, $include_directory = false) {
				if(!$this->exists($path))
					return false;
				if($include_directory)
					return $this->directoriesIncludingDirectory($path);
				else return $this->directoriesExcludingDirectory($path);
			}
			//recursive files
			public function recursiveFiles($path) {
				if(!$this->exists($path))
					return false;
				$entries=$this->files($path, true);
				$directories = $this->directories($path, true);
				foreach ($directories as $dir) { $entries=array_merge($entries, $this->recursiveFiles($dir, true)); }
				return $this->fromPathArrayToObjects($entries);
			}
			//recursive directories
			public function recursiveDirectories($path) {
				if(!$this->exists($path))
					return true;
				$entries=$this->directories($path, true);
				foreach ($entries as $dir) { $entries=array_merge($entries, $this->recursiveDirectories($dir, true)); }
				return $this->fromPathArrayToObjects($entries);
			}
			public function create($path) {
				$this->writableCheck($path);
				if(!$this->exists($path))
					return mkdir($path, 0777, true);
				return false;
			}
			public function move($src,$dst) {
				$this->writableCheck($dst);
				if($this->exists($src))
					return rename($src, $dst);
				return false;
			}
			public function copy($path, $dest) {
				$this->writableCheck($dest);
				if($this->exists($path)) {
					//create directory
					$this->create($dest);
					$entries = $this->scan($path, true);
					foreach ($entries as $entry) {
						if($this->_app->getFileHandler()->exists($entry)) {
							$this->_app->getFileHandler()->copy($entry, $dest."/".$this->getName($entry));
						} else if($this->exists($entry)) {
							$this->copy($entry, $dest."/".$this->getName($entry));
						}
					}
				}
				return false;
			}
			public function remove($path) {
				$this->writableCheck($path);
				if($this->exists($path)) {
					$entries = $this->scan($path, true);
					foreach ($entries as $entry) {
						if($this->exists($entry)) {
							$this->remove($entry);
						} else if($this->_app->getFileHandler()->exists($entry)) {
							$this->_app->getFileHandler()->remove($entry);
						}
					}
					rmdir($path);
					return true;
				}
				return false;
			}
		}
	}
