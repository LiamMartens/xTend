<?php
	//Configuration
	abstract class Config
	{
		const Url = "http://localhost:88";
	}
	//Errors
	abstract class Error
	{
		const NotFound = "404";
		const ClassNotFound = "ClassNotFound";
		const FileNotFoud = "FileNotFoud";
		const ControllerNotFound = "ControllerNotFound";
		const ViewNotFound = "ViewNotFound";
		const ModelNotFound = "ModelNotFound";
		const DatabaseConnectionFailed = "DatabaseConnectionFailed";
	}
	//Class autoloader
	function __autoload($class_name) {
		echo $class_name;
		if(File::Exists(App::System("Dynamic.$class_name").".php")) {
			App::Inc("Dynamic.$class_name");
			return true;
		}
		App::Error(Error::ClassNotFound);
		return false;
	}
	//Application
	abstract class App
	{
		//Current page variabless
		private static $_Model = null;
		private static $_Controller = null;
		private static $_View = null;
		public static function Model($model=null) {
			if($model==null) {
				return Self::$_Model;
			} else {
				Self::$_Model = $model;
			}
		}
		public static function Controller($controller=null) {
			if($controller==null) {
				return Self::$_Controller;
			} else {
				Self::$_Controller = $controller;
			}
		}
		public static function View($view=null) {
			if($view==null) {
				return Self::$_View;
			} else {
				Self::$_View = $view;
			}
		}
		//Url helpers for System files and for Web files
		public static function System($file = "") {
			$arr_path = explode(".",$file);
			$str_path = "../System";
			for($i = 0; $i < count($arr_path); $i++) {
				$str_path.=("/".$arr_path[$i]);
			}
			return $str_path;
		}
		public static function Web($file = "") {
			$arr_path = explode(".",$file);
			$str_path = Config::Url;
			for($i = 0; $i < count($arr_path); $i++) {
				$str_path.=("/".$arr_path[$i]);
			}
			return $str_path;
		}
		/* to include files */
		public static function Inc($inc) {
			$str_path = Self::System($inc);
			require($str_path.".php");
		}
		//Throw error
		public static function Error($error) {
			return Router::Error($error);
		}
		/* Initialize application */
		public static function Initialize() {
			//Initialize and include internals
			$internals = scandir(Self::System("Internals"));
			for($i = 2; $i < count($internals); $i++) {
				$internal = pathinfo(Self::System("Internals")."/".$internals[$i], PATHINFO_FILENAME);
				Self::Inc("Internals.$internal");
			}
			//Initialize and include libraries
			$libs = scandir(Self::System("Libs"));
			for($i = 2; $i < count($libs); $i++) {
				$lib = pathinfo(Self::System("Libs")."/".$libs[$i], PATHINFO_FILENAME);
				Self::Inc("Libs.$lib");
			}
			//Initialize and include BluePrints
			$blueprints = scandir(Self::System("Blueprints"));
			for($i = 2; $i < count($blueprints); $i++) {
				$blueprint = pathinfo(Self::System("BluePrints")."/".$blueprints[$i], PATHINFO_FILENAME);
				Self::Inc("BluePrints.$blueprint");
			}
			//Initialize and include Objects
			$objects = scandir(Self::System("Objects"));
			for($i = 2; $i < count($objects); $i++) {
				$object = pathinfo(Self::System("Objects")."/".$objects[$i], PATHINFO_FILENAME);
				Self::Inc("Objects.$object");
			}
			//Call pre configuration methods
			$classes = get_declared_classes();
			for($i = array_search("App",$classes)+1; $i < count($classes); $i++) {
				$class = $classes[$i];
				if(method_exists($class, "PreConfigInitialize")) {
					call_user_func(array($class,"PreConfigInitialize"));
				}
			}
			//Initialize and include configuration files
			$configs = scandir(Self::System("Config"));
			for($i = 2; $i < count($configs); $i++) {
				$conf = pathinfo(Self::System("Config")."/".$configs[$i], PATHINFO_FILENAME);
				Self::Inc("Config.$conf");
			}
			//Call Post configuration methods
			for($i = array_search("App",$classes)+1; $i < count($classes); $i++) {
				$class = $classes[$i];
				if(method_exists($class, "PostConfigInitialize")) {
					call_user_func(array($class,"PostConfigInitialize"));
				}
			}
		}
	}
	App::Initialize();
?>