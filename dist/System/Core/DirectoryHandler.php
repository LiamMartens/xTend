<?php
	namespace xTend
	{
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
			}
			public function systemDirectory($dirName) {
				$path=$this->_app->getSystemDirectory();
				$dir_parts = explode(".", $dirName);
				//foreach loop is possible here
				foreach ($dir_parts as $part) { $path.="/".$part; }
				return $path;
			}
			public function publicDirectory($dirName) {
				$path=$this->_app->getPublicDirectory();
				$dir_parts = explode(".", $dirName);
				//foreach loop is possible here
				foreach ($dir_parts as $part) { $path.="/".$part; }
				return $path;
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
				return glob($path."/*");
			}
			private function scanExcludingDirectory($path) {
				//no directory exists checking here as they are only used in scan and shouldn't be called separately
				//replace \\ with /
				$path=str_replace("\\", "/", $path);
				$contents = glob($path."/*"); $entries=[];
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
				return $entries;
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
				return array_merge(glob($path."/*", GLOB_ONLYDIR), glob($path."/.[!.][!.]*", GLOB_ONLYDIR));
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
				return $entries;
			}
			//recursive directories
			public function recursiveDirectories($path) {
				if(!$this->exists($path))
					return true;
				$entries=$this->directories($path, true);
				foreach ($entries as $dir) { $entries=array_merge($entries, $this->recursiveDirectories($dir, true)); }
				return $entries;
			}
			public function create($path) {
				if(!$this->exists($path))
					mkdir($path, 0777, true);
			}
			public function move($src,$dst) {
				if($this->exists($src))
					rename($src, $dst);
			}
			public function copy($path, $dest) {
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