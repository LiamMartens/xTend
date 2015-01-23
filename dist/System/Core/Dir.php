<?php
	namespace xTend
	{
		//Use following:
		use \RecursiveIteratorIterator as RecursiveIteratorIterator;
		use \RecursiveDirectoryIterator as RecursiveDirectoryIterator;

		class Dir
		{
			//Routing to a directory
			public static function System($DirPath) {
				$Path = "../System";
				$DirParts = explode(".", $DirPath);
				for($i=0;$i<count($DirParts);$i++) {
					$Path.="/".$DirParts[$i];
				}
				//Return path string
				return $Path;
			}
			public static function Web($DirPath) {
				$Path = "";
				$DirParts = explode(".", $DirPath);
				for($i=0;$i<count($DirParts);$i++) {
					if($i!=0) { $Path.="/"; }
					$Path.=$DirParts[$i];
				}
				//Return path string
				return Config::Url."/".$Path;	
			}
			//Check whether a directory exists
			public static function Exists($Path) {
				return is_dir($Path);
			}
			//Scan a directory for both files and directories
			public static function Scan($Path) {
				//Check for directory existance
				if(self::Exists($Path)) {
					$Contents = scandir($Path);
					//Unset /. and /.. entries
					unset($Contents[0]);
					unset($Contents[1]);
					//Return array values to reset keys
					return array_values($Contents);
				}
				return false;
			}
			//Scan a directory for files
			public static function Files($Path) {
				$Files = array();
				//Check whether dir exists and get all files and directories
				$Contents = self::Scan($Path);
				if($Contents) {
					//Directory exists
					foreach($Contents as $Content) {
						if(File::Exists("$Path/$Content")) {
							//it's a file
							$Files[] = trim(str_replace("\\","/",$Content),"/");
						}
					}
					//Return array values
					return $Files;
				}
				return false;
			}
			public static function RecursiveFiles($Path) {
				//Check whether directory exists
				if(self::Exists($Path)) {
				//Instantiate Iterator
					$Iterator = new RecursiveIteratorIterator(
						new RecursiveDirectoryIterator($Path, RecursiveDirectoryIterator::SKIP_DOTS),
						RecursiveIteratorIterator::SELF_FIRST,
						RecursiveIteratorIterator::CATCH_GET_CHILD
					);
					//Files array
					$Files = array();
					foreach ($Iterator as $IPath => $Dir) {
						if(File::Exists($IPath)) {
							//it's a file
							$Files[] = trim(str_replace("\\","/",substr($IPath,strlen($Path))),"/");
						}
					}
					return $Files;
				}
				return false;
			}
			//Scan for subdirectories
			public static function Directories($Path) {
				$Directories = array();
				//Check whether dir exists and get all files and directories
				$Contents = self::Scan($Path);
				if($Contents) {
					//Directory exists
					foreach($Contents as $Content) {
						if(self::Exists("$Path/$Content")) {
							//it's a Directory
							$Directories[] = trim(str_replace("\\","/",$Content),"/");
						}
					}
					//Return array values
					return $Directories;
				}
				return false;
			}
			public static function RecursiveDirectories($Path) {
				//Check whether directory exists
				if(self::Exists($Path)) {
				//Instantiate Iterator
					$Iterator = new RecursiveIteratorIterator(
						new RecursiveDirectoryIterator($Path, RecursiveDirectoryIterator::SKIP_DOTS),
						RecursiveIteratorIterator::SELF_FIRST,
						RecursiveIteratorIterator::CATCH_GET_CHILD
					);
					//Directories array
					$Directories = array();
					foreach ($Iterator as $IPath => $Dir) {
						if(self::Exists($IPath)) {
							//it's a directory
							$Directories[] = trim(str_replace("\\","/",substr($IPath,strlen($Path))),"/");
						}
					}
					return $Directories;
				}
				return false;
			}
			//Rename a directory
			public static function Rename($Source,$Destination) {
				//Check for existance of directory
				if(self::Exists($Source)) {
					rename($Source, $Destination);
				}
			}
			//Move a directory (alias for rename)
			public static function Move($Source, $Destination) {
				self::Rename($Source, $Destination);
			}
			//Copy a directory
			public static function Copy($Source, $Destination) {
				$Directories = self::RecursiveDirectories($Source);
				$Files = self::RecursiveFiles($Source);
				if($Directories&&$Files) {
					//First make all the necessary directories
					mkdir($Destination);
					foreach($Directories as $Dir) {
						mkdir("$Destination/$Dir");
					}
					//Copy all files
					foreach($Files as $File) {
						copy("$Source/$File", "$Destination/$File");
					}
					//Remove orignal directory
					self::Remove($Source);
				}
			}
			//Create directory
			public static function Create($Path) {
				if(!self::Exists($Path)) {
					mkdir($Path,0777,true);
				}
				return true;
			}
			//Remove directory
			public static function Remove($Path) {
				//Check whether directory actually exists
				if(self::Exists($Path)) {
					//Get all files and subdirectories
					$Contents = self::Scan($Path);
					//Loop through
					foreach($Contents as $Content) {
						if(self::Exists("$Path/$Content")) {
							//Its a directory
							self::Remove("$Path/$Content");
						} else if(File::Exists("$Path/$Content")) {
							//It's a file
							unlink("$Path/$Content");
						}
					}
					//Reset Contents keys
					reset($Contents);
					//Remove intial dir
					rmdir($Path);
				}
				return true;
			}
			//zip a folder

		}
	}
?>