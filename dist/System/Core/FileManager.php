<?php
	namespace xTend
	{
		class FileManager
		{
			private static $_IncludedFiles = array("../System/Core/FileManager.php");
			public static function IncludeFile($FilePath) {
				if(self::IsIncluded($FilePath)) return false;
				self::$_IncludedFiles[] = $FilePath;
				require_once($FilePath);
				return true;
			}
			public static function IncludeFiles($FilePaths) {
				foreach($FilePaths as $f) { self::IncludeFile($f); }
			}
			public static function IsIncluded($FilePath) {
				return ((array_search($FilePath, self::$_IncludedFiles)===false) ? false : true);
			}
		}
	}