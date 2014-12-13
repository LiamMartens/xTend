<?php
	abstract class File
	{
		public static function Exists($path) {
			return file_exists($path);
		}
		
		public static function Remove($path) {
			return unlink($path);
		}
		
		public static function Get($path) {
			return file_get_contents($path);
		}
		
		public static function Put($path,$value) {
			return file_put_contents($path,$value);
		}
	}
?>