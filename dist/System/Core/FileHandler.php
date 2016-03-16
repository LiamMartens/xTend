<?php
	namespace xTend;
	class FileHandler
	{
		private $_app;
		public function __construct($app) {
			//store containing app reference so the FileHandler can use it's directives
			$this->_app = $app;
			$this->_app->getErrorCodeHandler()->registerErrorCode(0x0005, "filehandler:file-not-writable", "A file you are tryig to write to is not writable");
		}
		public function writableCheck($path) {
			if(!is_writable($path)&&is_file($path)) {
				throw $this->_app->getErrorCodeHandler()->findError(0x0005)->getException();
				die($path); }
		}
		public function systemFile($fileName) {
			$path=$this->_app->getSystemDirectory();
			$file_parts = explode(".", $fileName);
			//for loop here since we need to exclude the last part of the array -> extension
			$file_parts_count = count($file_parts)-1;
			for($i=0;$i<$file_parts_count;$i++) { $path.="/".$file_parts[$i]; }
			//add extension part
			$path.=".".$file_parts[$file_parts_count];
			return $path;
		}
		public function publicFile($fileName) {
			$path=$this->_app->getPublicDirectory();
			$file_parts = explode(".", $fileName);
			//for loop here since we need to exclude the last part of the array -> extension
			$file_parts_count = count($file_parts)-1;
			for($i=0;$i<$file_parts_count;$i++) { $path.="/".$file_parts[$i]; }
			//add extension part
			$path.=".".$file_parts[$file_parts_count];
			return $path;
		}
		//helper function to get the last part of the path
		private function getName($path) {
			//replace \\ with /
			$path=str_replace("\\", "/", $path);
			$pos_back = strrpos($path, "/");
			return ($pos_back===false) ? $path : substr($path, $pos_back);
		}
		public function exists($filePath) {
			return is_file($filePath);
		}
		public function move($src,$dest) {
			//renaming a file is also possible using move
			$this->_app->getDirectoryHandler()->writableCheck($dest);
			if($this->exists($src))
				rename($src, $dest);
		}
		public function copy($src,$dest) {
			$this->_app->getDirectoryHandler()->writableCheck($dest);
			if($this->exists($src))
				copy($src, $dest);
		}
		public function read($path) {
			$handle = fopen($path, 'r');
			$content = (filesize($path)>0) ? fread($handle, filesize($path)) : "";
			fclose($handle);
			return $content;
		}
		public function write($path, $content) {
			$this->writableCheck($path);
			$handle = fopen($path, 'w');
			fwrite($handle, $content);
			fclose($handle);
		}
		public function append($path, $content) {
			$this->writableCheck($path);
			$handle = fopen($path, 'a');
			fwrite($handle, $content);
			fclose($handle);
		}
		public function remove($path) {
			if($this->exists($path)) {
				$this->writableCheck($path);
				unlink($path);
				return true;
			}
			return false;
		}
		public function setFileMeta($path, $key, $value) {
			//end func call if file doesn't exist
			if($this->exists($path)) {
				$metaFile = $this->_app->getDirectoryHandler()->systemDirectory($this->_app->getMetaDirectory())."/".hash("sha256", $path).".meta";
				$meta = []; if($this->exists($metaFile)) $meta=json_decode($this->read($metaFile), true);
				$meta[$key]=$value;
				$this->write($metaFile, json_encode($meta));
				return true;
			}
			return false;
		}
		public function getFileMeta($path, $key, $default=false) {
			if($this->exists($path)) {
				$metaFile = $this->_app->getDirectoryHandler()->systemDirectory($this->_app->getMetaDirectory())."/".hash("sha256", $path).".meta";
				$meta = []; if($this->exists($metaFile)) $meta=json_decode($this->read($metaFile), true);
				if(array_key_exists($key, $meta))
					return $meta[$key];
				else return $default;
			}
			return $default;
		}
	}