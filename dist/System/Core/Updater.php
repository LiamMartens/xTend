<?php
	namespace xTend
	{
		class Updater
		{
			private static $_ExcludeFiles = array();
			private static $_ExcludeDirectories = array("Config");
			//setters
			public static function ExcludeFiles($Files) { self::$_ExcludeFiles = $Files; }
			public static function ExcludeDirectories($Directories) { self::$_ExcludeDirectories = $Directories; }
			//read from github
			private static function Read($Url) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $Url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$data = curl_exec($ch);
				curl_close($ch);
				return $data;
			}
			//update methods
			public static function Check() {
				$DistConfig = self::Read("https://raw.githubusercontent.com/LiamMartens/xTend/master/dist/System/Core/Config.php");
				$matches; preg_match('/(const xTendVersion)(\s*)(=)(\s*)(\"|\')([\d\.]+)(\"|\')(;)/', $DistConfig, $matches);
				$DistVersion = intval(str_replace(".", "", $matches[6]));
				$CurrentVersion = intval(str_replace(".", "", Config::xTendVersion));
				if($DistVersion>$CurrentVersion) {
					if(is_writable(Dir::System(""))) {
						self::Update();
					} else { echo "Please make sure the `System` directory is writable"; }
				}
 			}
			public static function Update() {
				//download GitHub Master
				File::Write(
					File::System("Update.xTend.zip"),
					fopen("https://github.com/LiamMartens/xTend/archive/master.zip", 'r')
				);
				//unpack zip dir
				$zip = new Archive(File::System("Update.xTend.zip"), true);
				$zip->Extract(Dir::System("Update"));
				//remove zip
				File::Remove(File::System("Update.xTend.zip"));
				//update necessary files
				$Directories = Dir::RecursiveDirectories(Dir::System("Update.xTend-master.dist.System"));
				foreach($Directories as $Dir) {
					//check whether dir is excluded
					$DirPath = Dir::System("Update.xTend-master.dist.System")."/$Dir";
					if(array_search($Dir, self::$_ExcludeDirectories)===false) {
						$Files = Dir::Files($DirPath);
						//loop through files
						foreach($Files as $File) {
							if((array_search("$Dir/$File", self::$_ExcludeFiles)===false)&&
								("$Dir/$File"!=="Core/Config.php")) {
								File::Copy("$DirPath/$File", Dir::System("")."$Dir/$File");
							}
						}
					}
				}
				//change version number
				$NewConfig; preg_match('/(const xTendVersion)(\s*)(=)(\s*)(\"|\')([\d\.]+)(\"|\')(;)/', File::Read(File::System("Update.xTend-master.dist.System.Core.Config.php")), $NewConfig);
				$Config = File::Read(File::System("Core.Config.php"));
				$Config = preg_replace('/(const xTendVersion)(\s*)(=)(\s*)(\"|\')([\d\.]+)(\"|\')(;)/', 'const xTendVersion = "'.$NewConfig[6].'";', $Config);
				//remove temp dir
				Dir::Remove(Dir::System("Update"));
			}	

		}
	}