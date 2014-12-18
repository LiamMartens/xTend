<?php
	namespace xTend
	{
		//Require Core Files -> relative to /www directory
		require_once("../System/Core/Config.php");
		require_once("../System/Core/File.php");
		require_once("../System/Core/Dir.php");
		require_once("../System/Core/Error.php");
		require_once("../System/Core/Log.php");
		require_once("../System/Core/Models.php");
		require_once("../System/Core/Controllers.php");
		require_once("../System/Core/Views.php");
		require_once("../System/Core/Router.php");
		//xTend Application class
		class App
		{
			//Keep track of current Model, Controller and View
			private static $_Model = null;
			private static $_Controller = null;
			private static $_View = null;
			public static function Model($Model = false) {
				if($Model===false) {
					return Self::$_Model;
				} else {
					Self::$_Model = $Model;
				}
			}
			public static function Controller($Controller = false) {
				if($Controller === false) {
					return Self::$_Controller;
				} else {
					Self::$_Controller = $Controller;
				}
			}
			public static function View($View = false) {
				if($View === false) {
					return Self::$_View;
				} else {
					Self::$_View = $View;
				}
			}
			//Error handling
			public static function PHPError($No, $Message, $File, $Line) {
				Log::PHPError("$Message in $File on $Line");
			}
			public static function PHPException($Exception) {
				Log::PHPException($Exception->getMessage());
			}
			public static function Error($ErrorType, $Message = "") {
				Log::AppError($ErrorType, $Message);
				return Router::ThrowError($ErrorType);
			}
			//Request URL
			public static function RequestUrl() {
				return $_SERVER['REQUEST_URI'];
			}
			//File inclusion methods
			public static function IncludeFile($FilePath) {
				//Just one file --> extension included
				require(File::System($FilePath));
			}
			public static function IncludeFileName($FileName) {
				//Raw file inclusion
				require($FileName);
			}
			//Include a complete directory
			public static function IncludeDirectory($DirPath) {
				//Get directory root
				$Root = Dir::System($DirPath);
				//Fetch all subdirectories
				$SubDirectories = array_merge(array($Root), Dir::RecursiveDirectories($Root));
				//Filter out .exclude directories, check .ignore files and take .order files
				//To check the their subdirs for exclusion -> regex /^(DIR)|^(DIR)|.../
				$ExcludedLibraries = "";
				//All to be included directories with their respective order and ignore arrays
				for($i=0;$i<count($SubDirectories);$i++) {
					//Get directory path
					$Directory = ($i==0) ? $SubDirectories[$i] : "$Root/".$SubDirectories[$i];
					//Is this directory exclded?
					if(!File::Exists("$Directory/.exclude")) {
						//Check already excluded libraries (if this is child dir)
						if((preg_match("/$ExcludedLibraries/i", $Directory)!=1)||($ExcludedLibraries=="")) {
							//Get ignore file -> which files should not be included
							$Ignore = explode("\n",str_replace("\r\n","\n",File::Read("$Directory/.ignore")));
							$Order = explode("\n",str_replace("\r\n","\n",File::Read("$Directory/.order")));
							//Fetch dir files -> not recursive!
							$Files = Dir::Files($Directory);
							//Unset .ignore, .exclude and .order files
							if(array_search(".ignore", $Files)!==false) { unset($Files[array_search(".ignore", $Files)]); };
							if(array_search(".exclude", $Files)!==false) { unset($Files[array_search(".exclude", $Files)]); };
							if(array_search(".order", $Files)!==false) { unset($Files[array_search(".order", $Files)]); };
							//Include necessary files
							//First loop through Order
							foreach($Order as $File) {
								//Check whether file exists
								if(File::Exists("$Directory/$File")&&(array_search($File, $Ignore)===false)) {
									//Include file
									Self::IncludeFileName("$Directory/$File");
									//Remove from files array
									unset($Files[array_search($File, $Files)]);
								}
							}
							//Loop through rremaining files
							foreach($Files as $File) {
								if(File::Exists("$Directory/$File")&&(array_search($File, $Ignore)===false)) {
									//Include file
									Self::IncludeFileName("$Directory/$File");
								}
							}
							//DONE
						}
					} else {
						//Add to excludedlibraries regex
						$ExcludedLibraries.="|";
						$ExcludedLibraries .= "^(".preg_quote($Directory,"/").")";
					}
				}
			}
			//Get xTend Classes start
			private static function xTendStart() {
				$Classes = get_declared_classes();
				$xTendStart = 0;
				for($i=0;$i<count($Classes);$i++) {
					$Class=$Classes[$i];
					$ClassExplode = explode("\\", $Class);
					if($ClassExplode[0]=="xTend") {
						$xTendStart = $i;
						break;
					}
				}
				return $xTendStart;
			}
			//Call Pre configuration methods
			private static function PreConfigure() {
				$xTendStart = Self::xTendStart();
				$Classes = get_declared_classes();
				for($i=$xTendStart;$i<count($Classes);$i++) {
					//Check for preconfig method
					$Class = $Classes[$i];
					if(method_exists("$Class", "PreConfiguration")) {
						//Call
						call_user_func(array($Class, "PreConfiguration"));
					}
				}
			}
			//Call Post Configuration methods
			private static function PostConfigure() {
				$xTendStart = Self::xTendStart();
				$Classes = get_declared_classes();
				for($i=$xTendStart;$i<count($Classes);$i++) {
					//Check for preconfig method
					$Class = $Classes[$i];
					if(method_exists("$Class", "PostConfiguration")) {
						//Call
						call_user_func(array($Class, "PostConfiguration"));
					}
				}
			}
			//Initialize
			public static function Initialize() {
				//Set charset
				header('Content-Type:text/html;charset=utf-8');
				//Set default timezone
				date_default_timezone_set("UTC");
				//Set error handlers
				set_error_handler("xTend\App::PHPError", E_ALL);
				set_exception_handler("xTend\App::PHPException");
				//Include all necessary files
				App::IncludeDirectory("Libs");
				App::IncludeDirectory("Blueprints");
				App::IncludeDirectory("Objects");
				//Call PreConfigure
				Self::PreConfigure();
				//Include Configs
				App::IncludeDirectory("Config");
				//Call PostConfigure
				//The router will have a postconfiguration method to route to the MVC
				Self::PostConfigure();
			}
		}
		//Call App Init
		App::Initialize();
	}
?>