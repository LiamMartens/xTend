<?php
	namespace xTend\Core\FileHandler {
		class File {
			private $_app;
			private $_path;
			private function cleanPath($path) { return trim(rtrim(str_replace('\\', '/', $path), '/')); }
			public function setPath($path) { $this->_path = $this->cleanPath($path); }
			public function __construct($app, $path) {
				$this->_app = $app;
				//always use setPath in order to automatically clean the path
				//aka replace \ with / as this is better
				$this->setPath($path);
			}
			public function exists() {
				return is_file($this->_path);
			}
			public function writable() {
				return (is_writable($this->_path)&&$this->exists());
			}
			public function name() {
				//returns the name of the file
				$sl_pos = strrpos($this->_path, '/');
				return substr($this->_path, $sl_pos + 1);
			}
			public function parent() {
				$sl_pos = strrpos($this->_path, '/');
				return new \xTend\Core\DirectoryHandler\Directory($this->_app, substr($this->_path, 0, $sl_pos));
			}
			public function move($dest) {
				//try creating parents first
				if((new File($this->_app, $dest))->parent()->create())
					return rename($this->_path, $dest);
				return false;
			}
			public function copy($dest) {
				//try creating parents first
				if((new File($this->_app, $dest))->parent()->create())
					return copy($this->_path, $dest);
				return false;
			}
			public function remove() {
				return unlink($this->_path);
			}
			public function read() {
				if($this->exists()) {
					$handle = fopen($this->_path, 'r');
					$content = (filesize($this->_path)>0) ? fread($handle, filesize($this->_path)) : false;
					fclose($handle);
					return $content;
				} return false;
			}
			public function write($content) {
				if($this->parent()->writable()) {
					$handle = fopen($this->_path, 'w');
					fwrite($handle, $content);
					fclose($handle);
					return true;
				} return false;
			}
			public function append($content) {
				if($this->writable()) {
					$handle = fopen($this->_path, 'a');
					fwrite($handle, $content);
					fclose($handle);
					return true;
				} return false;
			}
			public function getMeta($key, $default = false) {
				if($this->exists()) {
					$m_file = $this->_app->getMetaDirectory()->file(hash("sha256", $this->_path).".meta");
					$meta=[]; if($m_file->exists()) $meta=json_decode($m_file->read(), true);
					if(array_key_exists($key, $meta)) return $meta[$key];
				}
				return $default;
			}
			public function setMeta($key, $value = null) {
				//set or remove meta
				if($this->exists()) {
					$m_file = $this->_app->getMetaDirectory()->file(hash("sha256", $this->_path).".meta");
					$meta=[]; if($m_file->exists()) $meta=json_decode($m_file->read(), true);
					if($value!==null) {
						$meta[$key]=$value;
					} elseif(array_key_exists($key, $meta)) { unset($meta[$key]); }
					return $m_file->write(json_encode($meta));
				}
				return false;
			}
			public function __toString() {
				return $this->_path;
			}
		}
	}
	namespace xTend\Core {
		class FileHandler
		{
			private $_app;
			public function __construct($app) {
				//store containing app reference so the FileHandler can use it's directives
				$this->_app = $app;
			}
			public function system($fileName, $ext_count = 1) {
				$path=$this->_app->getSystemDirectory();
				$file_parts = explode(".", $fileName);
				//for loop here since we need to exclude the last part of the array -> extension
				$file_parts_count = count($file_parts)-$ext_count;
				for($i=0;$i<$file_parts_count;$i++) { $path.="/".$file_parts[$i]; }
				//add extension part
				for($i=$file_parts_count;$i<count($file_parts);$i++) { $path.=".".$file_parts[$i]; }
				return new FileHandler\File($this->_app, $path);
			}
			public function public($fileName, $ext_count = 1) {
				$path=$this->_app->getPublicDirectory();
				$file_parts = explode(".", $fileName);
				//for loop here since we need to exclude the last part of the array -> extension
				$file_parts_count = count($file_parts)-$ext_count;
				for($i=0;$i<$file_parts_count;$i++) { $path.="/".$file_parts[$i]; }
				//add extension part
				for($i=$file_parts_count;$i<count($file_parts);$i++) { $path.=".".$file_parts[$i]; }
				return new FileHandler\File($this->_app, $path);
			}
		}
	}
