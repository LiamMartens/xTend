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
			public static function PHPError($No, $Message) {
				Log::PHPError($Message);
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
			public static function IncludeDirectory($DirPath) {
				/**
					CHECK FOR .ignore file in every subdirectory to determin what
					files should not be included
				**/
				$DirLoc = Dir::System($DirPath);
				//Get all subdirectories
				$Directories = Dir::RecursiveDirectories($DirLoc);
				//First check root dirctory
				$Files = Dir::Files($DirLoc);
				$Ignore  = File::Read("$DirLoc/$Directory/.ignore");
				if($Ignore!==false) {
					$Ignore = str_replace("\r\n", "\n", $Ignore);
					$Ignore = explode("\n", $Ignore);
				}
				foreach($Files as $File) {
					if((!in_array($File, $Ignore))&&($File!=".ignore")) {
						require("$DirLoc/$File");
					}
				}
				//Now check subdirs
				foreach($Directories as $Directory) {
					$Files = Dir::Files("$DirLoc/$Directory");
					$Ignore  = File::Read("$DirLoc/$Directory/.ignore");
					if($Ignore!==false) {
						$Ignore = str_replace("\r\n", "\n", $Ignore);
						$Ignore = explode("\n", $Ignore);
					}
					foreach($Files as $File) {
						if((!in_array($File, $Ignore))&&($File!=".ignore")) {
							require("$DirLoc/$Directory/$File");
						}
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