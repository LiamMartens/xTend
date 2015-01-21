<?php
	namespace xTend
	{
		class File
		{
			//Routing to files
			public static function System($FilePath) {
				$Path = "../System";
				$FileParts = explode(".", $FilePath);
				for($i=0;$i<count($FileParts)-1;$i++) {
					$Path.="/".$FileParts[$i];
				}
				//Add extension
				$Path.=".".$FileParts[count($FileParts)-1];
				//Return path string
				return $Path;
			}
			public static function Web($FilePath) {
				$Path = "";
				$FileParts = explode(".", $FilePath);
				for($i=0;$i<count($FileParts)-1;$i++) {
					if($i!=0) { $Path .= "/"; }
					$Path.=$FileParts[$i];
				}
				//Add extension
				$Path.=".".$FileParts[count($FileParts)-1];
				//Return path string
				return Config::Url."/".$Path;	
			}
			//Check whether a file exists
			public static function Exists($FilePath) {
				return is_file($FilePath);
			}
			//Rename a file
			public static function Rename($Source, $Destination) {
				//Check for file existance
				if(self::Exists($FilePath)) {
					rename($Source, $Destination);
				}
			}
			//Copy a file
			public static function Copy($Source, $Destination) {
				//Check for file existance
				if(self::Exists($FilePath)) {
					copy($Source, $Destination);
				}
			}
			//Move a file (alias for rename)
			public static function Move($Source, $Destination) {
				self::Rename($Source, $Destination);
			}
			//Remove a file
			public static function Remove($FilePath) {
				//Check whether file actually exists
				if(self::Exists($FilePath)) {
					return unlink($FilePath);
				}
				return false;
			}
			//Get the file contents of a file
			public static function Read($FilePath) {
				//Check whether file actually exists
				if(self::Exists($FilePath)) {
					return file_get_contents($FilePath);
				}
				return false;
			}
			//Write to a file (overwrite)
			public static function Write($FilePath, $FileContent) {
				return file_put_contents($FilePath, $FileContent);
			}
			//Append to a file
			public static function Append($FilePath, $FileContent) {
				return file_put_contents($FilePath, $FileContent, FILE_APPEND);
			}
			//set meta data
			public static function SetMeta($File, $Key, $Value) {
				//check file existance
				if(!self::Exists($File)) { return false; }
				//get meta
				$metaFileName = Dir::System("Meta")."/".hash("sha256",$File);
				$meta = array();
				if(self::Exists($metaFileName)) { $meta = json_decode(self::Read($metaFileName),true);  }
				$meta[$Key] = $Value;
				return self::Write($metaFileName, json_encode($meta));
			}
			//get meta data
			public static function GetMeta($File, $Key) {
				if(!self::Exists($File)) { return false; }
				$metaFileName = Dir::System("Meta")."/".hash("sha256",$File);
				$meta = array();
				if(self::Exists($metaFileName)) { $meta = json_decode(self::Read($metaFileName),true);  }
				if(array_key_exists($Key, $meta)) {
					return $meta[$Key];
				}
				return false;
			}
		}
	}
?>