<?php
	namespace xTend\Core\DirectoryHandler {
		class Directory {
			private $_app;
			private $_path;
			public function cleanPath($path) { return trim(rtrim(str_replace('\\', '/', $path), '/')); }
			public function setPath($path) { $this->_path = $this->cleanPath($path); }
			public function __construct($app, $path) {
				$this->_app = $app;
				//always use setPath in order to automatically clean the path
				//aka replace \ with / as this is better
				$this->setPath($path);
			}
			public function exists() {
				return is_dir($this->_path);
			}
			public function writable() {
				return (is_writable($this->_path)&&$this->exists());
			}
			public function name() {
				$sl_pos = strrpos($this->_path, '/');
				return substr($this->_path, $sl_pos + 1);
			}
			public function parent() {
				$sl_pos = strrpos($this->_path, '/');
				return new Directory($this->_app, substr($this->_path, 0, $sl_pos));
			}
			public function scan($recursive = false) {
				$entries=[];
				//fetch files recursive or not
				if($recursive) {
					foreach (new \RecursiveIteratorIterator(
							 new \RecursiveDirectoryIterator($this->_path,
								\RecursiveDirectoryIterator::SKIP_DOTS),
								\RecursiveIteratorIterator::SELF_FIRST) as $file) {
						$entries[] = $file;
					}
				} else { $entries = array_merge(glob($this->_path."/*"), glob($this->_path."/.[!.][!.]*")); }
				//run map on entries for class creation
				return array_map(function($entry) {
					if(is_file($entry)) {
						return new \xTend\Core\FileHandler\File($this->_app, $entry);
					} elseif(is_dir($entry)) { return new Directory($this->_app, $entry); }
				}, $entries);
			}
			public function files($recursive = false) {
				$entries = $this->scan($recursive);
				return array_filter($entries, function($entry) {
					return is_file($entry);
				});
			}
			public function directories($recursive = false) {
				$entries = $this->scan($recursive);
				return array_filter($entries, function($entry) {
					return is_dir($entry);
				});
			}
			public function create() {
				return mkdir($this->_path, 0777, true);
			}
			public function move($dest) {
				//try making directory first
				if((new Directory($dest))->create())
					return rename($this->_path, $dest);
				return false;
			}
			public function copy($dest) {
				$directories = $this->directories();
				$files = $this->files();
				//make destination dir
				$dest = new Directory($dest);
				if(!$dest->create()||!$dest->writable()) {
					//copy files to destination
					foreach($files as $file) { $file->copy($dest."/".$file->name()); }
					//repeat for directories
					foreach($directories as $dir) { $dir->copy($dest."/".$dir->name()); }
				}
				return false;
			}
			public function remove() {
				$files = $this->files();
				$directories = $this->directories();
				//remove all files
				foreach($files as $file) { $file->remove(); }
				//remove all directories
				foreach($directories as $dir) { $dir->remove(); }
				//remove current directory
				return rmdir($this->_path);
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
			}
			public function system($dirName) {
				$path=$this->_app->getSystemDirectory();
				$dir_parts = explode(".", $dirName);
				//foreach loop is possible here
				foreach ($dir_parts as $part) { $path.="/".$part; }
				return new DirectoryHandler\Directory($this->_app, $path);
			}
			public function public($dirName) {
				$path=$this->_app->getPublicDirectory();
				$dir_parts = explode(".", $dirName);
				//foreach loop is possible here
				foreach ($dir_parts as $part) { $path.="/".$part; }
				return new DirectoryHandler\Directory($this->_app, $path);
			}
		}
	}
