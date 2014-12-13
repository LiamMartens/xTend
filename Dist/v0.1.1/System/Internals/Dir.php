<?php
	abstract class Dir
	{
		public static function Exists($path) {
			return is_dir($path);
		}
		
		public static function Remove($path) {
			return rmdir($path);
		}
		
		public static function Create($path) {
			return mkdir($path);
		}
		
		public static function Files($path) {
			$path=trim($path,"/");
			$files = scandir($path);
			$_files = array();
			for($i=2;$i<count($files);$i++) {
				$filepath=$path."/".$files[$i];
				$file=$files[$i];
				if(!is_dir($filepath)&&file_exists($filepath)) {
					$_files[] = $file;
				}
			}
			return $_files;
		}
		
		public static function Directories($path) {
			$path=trim($path,"/");
			$dirs = scandir($path);
			$_dirs = array();
			for($i=2;$i<count($dirs);$i++) {
				$dirpath=$path."/".$dirs[$i];
				$dir=$dirs[$i];
				if(is_dir($dirpath)) {
					$_dirs[] = $dir;
				}
			}
			return $_dirs;
		}
		
		public static function Scan($path) {
			$entries = scandir($path);
			unset($entries[0]);
			unset($entries[1]);
			return array_values($entries);
		}
	}
?>