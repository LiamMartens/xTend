<?php
	namespace xTend\Core;
	class App
	{
		//moved core config from sperate const class to App
		private $_xTendVersion = "0.8.7";
		private $_url = "http://localhost";
		private $_inDevelopment = false;
		private $_backupInterval = "1 week";
		private $_backupLimit = 10;
		private $_logLimit = 30;
		private $_namespace = "Application";
		//app configuration getters
		public function getVersion() { return $this->_xTendVersion; }
		public function getUrl() { return $this->_url; }
		public function getDevelopmentStatus() { return $this->_inDevelopment; }
		public function getBackupInterval() { return $this->_backupInterval; }
		public function getBackupLimit() { return $this->_backupLimit; }
		public function getLogLimit() { return $this->_logLimit; }
		public function getNamespace() { return $this->_namespace; }
		/**
			app configuration setters -> if needed to set during runtime (for a cms for exmaple)
			-> not for xTend version.
		**/
		public function setUrl($url) { $this->_url = $url; }
		public function setDevelopmentStatus($status) { $this->_inDevelopment = $status; }
		public function setBackupInterval($interval) { $this->_backupInterval = $interval; }
		public function setBackupLimit($limit) { $this->_backupLimit = $limit; }
		public function setLogLimit($limit) { $this->_logLimit = $limit; }
		public function configuration($confvalues) {
			//set directory settings using an array
			foreach ($confvalues as $key => $value) {
				$f_name = 'set'.$key;
				$this->$f_name($value);
			}
		}
		//directory location configuration
		private $_dirBackups = "Backups";
		public function setBackupsDirectory($dir) { $this->_dirBackups = $this->getDirectoryHandler()->system($dir); }
		public function getBackupsDirectory() { return $this->_dirBackups; }
		private $_dirBlueprints = "Blueprints";
		public function setBlueprintsDirectory($dir) { $this->_dirBlueprints = $this->getDirectoryHandler()->system($dir); }
		public function getBlueprintsDirectory() { return $this->_dirBlueprints; }
		private $_dirConfig = "Config";
		public function setConfigDirectory($dir) { $this->_dirConfig = $this->getDirectoryHandler()->system($dir); }
		public function getConfigDirectory() { return $this->_dirConfig; }
		private $_dirControllers = "Controllers";
		public function setControllersDirectory($dir) { $this->_dirControllers = $this->getDirectoryHandler()->system($dir); }
		public function getControllersDirectory() { return $this->_dirControllers; }
		private $_dirLayouts = "Layouts";
		public function setLayoutsDirectory($dir) { $this->_dirLayouts = $this->getDirectoryHandler()->system($dir); }
		public function getLayoutsDirectory() { return $this->_dirLayouts; }
		private $_dirLibs = "Libs";
		public function setLibsDirectory($dir) { $this->_dirLibs = $this->getDirectoryHandler()->system($dir); }
		public function getLibsDirectory() { return $this->_dirLibs; }
		private $_dirLogs = "Logs";
		public function setLogsDirectory($dir) { $this->_dirLogs = $this->getDirectoryHandler()->system($dir); }
		public function getLogsDirectory() { return $this->_dirLogs; }
		private $_dirMeta = "Meta";
		public function setMetaDirectory($dir) { $this->_dirMeta = $this->getDirectoryHandler()->system($dir); }
		public function getMetaDirectory() { return $this->_dirMeta; }
		private $_dirModels = "Models";
		public function setModelsDirectory($dir) { $this->_dirModels = $this->getDirectoryHandler()->system($dir); }
		public function getModelsDirectory() { return $this->_dirModels; }
		private $_dirModules = "Modules";
		public function setModulesDirectory($dir) { $this->_dirModules = $this->getDirectoryHandler()->system($dir); }
		public function getModulesDirectory() { return $this->_dirModules; }
		private $_dirObjects = "Objects";
		public function setObjectsDirectory($dir) { $this->_dirObjects = $this->getDirectoryHandler()->system($dir); }
		public function getObjectsDirectory() { return $this->_dirObjects; }
		private $_dirViewOutput = "ViewOutput";
		public function setViewOutputDirectory($dir) { $this->_dirViewOutput = $this->getDirectoryHandler()->system($dir); }
		public function getViewOutputDirectory() { return $this->_dirViewOutput; }
		private $_dirViews = "Views";
		public function setViewsDirectory($dir) { $this->_dirViews = $this->getDirectoryHandler()->system($dir); }
		public function getViewsDirectory() { return $this->_dirViews; }
		public function directories($dirvalues) {
			//set directory settings using an array
			foreach ($dirvalues as $dir => $value) {
				$f_name = 'set'.$dir.'Directory';
				$this->$f_name($value);
			}
		}
		//Application defined variables
		private $_dirSystem;
		private $_dirPublic;
		//application defined directives getters
		public function getSystemDirectory() { return $this->_dirSystem;}
		public function getPublicDirectory() { return $this->_dirPublic;}
		//bootstap mode should only be set at the start of the application
		private $_bootstrapMode;
		public function getBootstrapMode() { return $this->_bootstrapMode; }
		//application settings container -> at least one for every app instance to keep track of any settings you want to save
		//apart from all the necessary stuff
		private $_settingsContainer;
		public function getSettingsContainer() { return $this->_settingsContainer; }
		//application file hanlder -> one for every application to use their respective directives
		private $_fileHandler;
		public function getFileHandler() { return $this->_fileHandler; }
		//application directory handler
		private $_directoryHandler;
		public function getDirectoryHandler() { return $this->_directoryHandler; }
		//status code handler
		private $_statusCodeHandler;
		public function getStatusCodeHandler() { return $this->_statusCodeHandler; }
		//log handler
		private $_logHandler;
		public function getLogHandler() { return $this->_logHandler; }
		//model handler
		private $_modelHandler;
		public function getModelHandler() { return $this->_modelHandler; }
		//controller handler
		private $_controllerHandler;
		public function getControllerHandler() { return $this->_controllerHandler; }
		//view handler
		private $_viewHandler;
		public function getViewHandler() { return $this->_viewHandler; }
		//URL handle
		private $_UrlHandler;
		public function getUrlHandler() { return $this->_UrlHandler; }
		//Router
		private $_router;
		public function getRouter() { return $this->_router; }
		//BackupManager
		private $_backupManager;
		public function getBackupManager() { return $this->_backupManager; }
		//file manager
		private $_fileManager;
		public function getFileManager() { return $this->_fileManager; }
		//SortHelper
		private $_sortHelper;
		public function getSortHelper() { return $this->_sortHelper; }
		//Wow layout engine
		private $_wowCompiler;
		public function getWowCompiler() { return $this->_wowCompiler; }
		//request data handler
		private $_requestDataHandler;
		public function getRequestDataHandler() { return $this->_requestDataHandler; }
		//Simple HTML writer
		private $_htmlHandler;
		public function getHTMLHandler() { return $this->_htmlHandler; }
		//FormTokenHandler
		private $_formTokenHandler;
		public function getFormTokenHandler() { return $this->_formTokenHandler; }
		//packagist handler
		private $_packagistHandler;
		public function getPackagistHandler() { return $this->_packagistHandler; }
		//error throw
		public function throwError($code) {
			header("HTTP/1.0 $code");
			$error = $this->_statusCodeHandler->findStatus($code);
			if($error instanceof StatusCode) {
				$this->_logHandler->write($error, $_SERVER["REQUEST_URI"]."\t".$_SERVER["REMOTE_ADDR"]);
				return $this->_router->throwError($code);
			}
			return false;
		}
		//preconfiguration methods
		private $_preConfigMethods;
		public function addPreconfigurationMethod($fn) {$this->_preConfigMethods[]=$fn; }
		//postconfiguration methods
		private $_postConfigMethods;
		public function addPostConfigurationMethod($fn) {$this->_postConfigMethods[]=$fn; }
		//application integrity check
		private function applicationIntegrityCheck() {
			//check server user
			$user = exec('whoami');
			if($user=='root') {
				die("It's a bad practice to run your webserver as root.
					Make sure you run your webserver as a 'regular' user
					and ensure all files would be accessible to the user
					you will be using for the command line tool");
			}
			//check php version
			if (version_compare(phpversion(), '7.0.0', '<')) {
			    die("You need PHP 7 to use xTend");
			}
			//check directories
			$directories = [$this->_dirBackups,
                            $this->_dirBlueprints,
                            $this->_dirConfig,
							$this->_dirControllers,
							$this->_dirLayouts,
							$this->_dirLogs,
							$this->_dirModels,
							$this->_dirModules,
                            $this->_dirObjects,
							$this->_dirViewOutput,
							$this->_dirViews,
							$this->_dirMeta,
							$this->_dirLibs];
			$writable_system_directories = [$this->_dirBackups,$this->_dirLogs,$this->_dirViewOutput,$this->_dirMeta];
			$can_write = is_writable($this->_dirSystem);
			$integrity_success = true;
			foreach ($directories as $dir) {
				if(!$dir->exists()) {
					if((($can_write)&&($dir->create()===false))||(!$can_write)) {
						echo ("Failed to create System directory ".$dir."<br>"); $integrity_success=false;
					}
				}
			}
			foreach ($writable_system_directories as $dir) {
				$dir = $this->getDirectoryHandler()->system("$dir");
				if($dir->exists()&&!$dir->writable()) {
					echo $dir." is not writable<br>"; $integrity_success=false;
				}
			}
			if(!$integrity_success)
				die("<br>Integrity check failed");
		}
		//constructor
		public function __construct($ns, $public_directory, $bootstrap_mode = false) {
			//check variables and enter temp
			if(!isset($_SERVER['HTTP_USER_AGENT']))
				$_SERVER['HTTP_USER_AGENT']=sha1(uniqid().microtime());
			if(!isset($_SERVER['REMOTE_ADDR']))
				$_SERVER['REMOTE_ADDR']=sha1(uniqid().microtime());
			//set namespace
			$this->_namespace=$ns;
			//the directives are set automatically since they are very important to the application itself
			//set system directory
			$this->_dirSystem = substr(__DIR__,0,strlen(__DIR__)-5);
			//set public directory
			$this->_dirPublic = $public_directory;
			//set default time zone to UTC
			date_default_timezone_set("UTC");
			//set error and exception handlers are gone here since they can't be handled using mutliple apps since they can only call one method
			//the logging class remains for App specific logs
			//set bootstrap mode here
			$this->_bootstrapMode = $bootstrap_mode;
			//include ClassManager for further class inclusion
			require_once($this->_dirSystem."/Core/ClassManager.php");
			//using ClassManager include further needed classes
			//include and initialize SettingsContainer class
			ClassManager::includeClass("xTend\\Core\\SettingsContainer", $this->_dirSystem."/Core/SettingsContainer.php");
			$this->_settingsContainer = new SettingsContainer();
			//include archive class
			ClassManager::includeClass("xTend\\Core\\Archive", $this->_dirSystem."/Core/Archive.php");
			//include StatusCodeHandler
			ClassManager::includeClass("xTend\\Core\\StatusCodeHandler", $this->_dirSystem."/Core/StatusCodeHandler.php");
			$this->_statusCodeHandler = new StatusCodeHandler();
			//include dir class
			ClassManager::includeClass("xTend\\Core\\DirectoryHandler", $this->_dirSystem."/Core/DirectoryHandler.php");
			$this->_directoryHandler = new DirectoryHandler($this);
			$this->_dirSystem = new DirectoryHandler\Directory($this, $this->_dirSystem);
			$this->_dirPublic = new DirectoryHandler\Directory($this, $this->_dirPublic);
			//set directory settings as they are strings by default -> can't access necessary classes yet
			$this->setBackupsDirectory($this->getBackupsDirectory());
			$this->setBlueprintsDirectory($this->getBlueprintsDirectory());
			$this->setConfigDirectory($this->getConfigDirectory());
			$this->setControllersDirectory($this->getControllersDirectory());
			$this->setLayoutsDirectory($this->getLayoutsDirectory());
			$this->setLibsDirectory($this->getLibsDirectory());
			$this->setLogsDirectory($this->getLogsDirectory());
			$this->setMetaDirectory($this->getMetaDirectory());
			$this->setModelsDirectory($this->getModelsDirectory());
			$this->setModulesDirectory($this->getModulesDirectory());
			$this->setObjectsDirectory($this->getObjectsDirectory());
			$this->setViewOutputDirectory($this->getViewOutputDirectory());
			$this->setViewsDirectory($this->getViewsDirectory());
			//include file class
			ClassManager::includeClass("xTend\\Core\\FileHandler", $this->_dirSystem."/Core/FileHandler.php");
			$this->_fileHandler = new FileHandler($this);
			//include LogHandler
			ClassManager::includeClass("xTend\\Core\\LogHandler", $this->_dirSystem."/Core/LogHandler.php");
			$this->_logHandler = new LogHandler($this);
			//include ModelHandler
			ClassManager::includeClass("xTend\\Core\\ModelHandler", $this->_dirSystem."/Core/ModelHandler.php");
			$this->_modelHandler = new ModelHandler($this);
			//include ControllerHandler
			ClassManager::includeClass("xTend\\Core\\ControllerHandler", $this->_dirSystem."/Core/ControllerHandler.php");
			$this->_controllerHandler = new ControllerHandler($this);
			//Include view blueprints
			ClassManager::includeClass("xTend\\Blueprints\\BaseView", $this->_dirBlueprints->file("BaseView.php"));
			ClassManager::includeClass("xTend\\Blueprints\\BaseDataView", $this->_dirBlueprints->file("BaseDataView.php"));
			ClassManager::includeClass("xTend\\Objects\\View",  $this->_dirObjects->file("View.php"));
			//include ViewHandler
			ClassManager::includeClass("xTend\\Core\\ViewHandler", $this->_dirSystem."/Core/ViewHandler.php");
			$this->_viewHandler = new ViewHandler($this);
			//include UrlHandler
			ClassManager::includeClass("xTend\\Blueprints\\BaseDataExtension", $this->_dirBlueprints->file("BaseDataExtension.php"));
			ClassManager::includeClass("xTend\\Core\\UrlHandler", $this->_dirSystem."/Core/UrlHandler.php");
			$this->_UrlHandler = new UrlHandler($this);
			//include Route Object
			ClassManager::includeClass("xTend\\Objects\\Route", $this->_dirObjects->file("Route.php"));
			//include Router
			ClassManager::includeClass("xTend\\Core\\Router", $this->_dirSystem."/Core/Router.php");
			$this->_router = new Router($this);
			//include BackupManager
			ClassManager::includeClass("xTend\\Core\\BackupManager", $this->_dirSystem."/Core/BackupManager.php");
			$this->_backupManager = new BackupManager($this);
			//include crypto
			if(ClassManager::includeClass("Defuse\\Crypto\\CryptoLoader", $this->_dirSystem."/Core/Crypto/CryptoLoader.php"))
				\Defuse\Crypto\CryptoLoader::load();
			//include SessionHandler class
			ClassManager::includeClass("xTend\\Core\\Session", $this->_dirSystem."/Core/Session.php");
			ClassManager::includeClass("xTend\\Core\\SessionHandler", $this->_dirSystem."/Core/SessionHandler.php");
			//include Cookies class
			ClassManager::includeClass("xTend\\Core\\Cookie", $this->_dirSystem."/Core/Cookie.php");
			//include filemanager class
			ClassManager::includeClass("xTend\\Core\\FileManager", $this->_dirSystem."/Core/FileManager.php");
			$this->_fileManager = new FileManager();
			//include SortHelper
			ClassManager::includeClass("xTend\\Core\\SortHelper", $this->_dirSystem."/Core/SortHelper.php");
			$this->_sortHelper = new SortHelper();
			//include Wow Compiler
			ClassManager::includeClass("xTend\\Core\\Wow", $this->_dirSystem."/Core/Wow.php");
			$this->_wowCompiler = new Wow($this);
			//include RequestDataHandler
			ClassManager::includeClass("xTend\\Core\\RequestDataHandler", $this->_dirSystem."/Core/RequestDataHandler.php");
			$this->_requestDataHandler = new RequestDataHandler($this);
			//include HTMLHandler
			ClassManager::includeClass("xTend\\Core\\HTMLHandler", $this->_dirSystem."/Core/HTMLHandler.php");
			$this->_htmlHandler = new HTMLHandler($this);
			//include FormTokenHandler
			ClassManager::includeClass("xTend\\Core\\FormTokenHandler", $this->_dirSystem."/Core/FormTokenHandler.php");
			$this->_formTokenHandler = new FormTokenHandler($this);
			//include VersionCheck helper
			ClassManager::includeClass("xTend\\Core\\VersionCheck", $this->_dirSystem."/Core/VersionCheck.php");
			//include PackagistHandler
			ClassManager::includeClass("xTend\\Core\\PackagistHandler", $this->_dirSystem."/Core/PackagistHandler.php");
			$this->_packagistHandler = new PackagistHandler($this);
			//inlcude Controller and model bluepprints
			ClassManager::includeClass("xTend\\Blueprints\\BaseController", $this->_dirBlueprints->file("BaseController.php"));
			ClassManager::includeClass("xTend\\Blueprints\\BaseDataController", $this->_dirBlueprints->file("BaseDataController.php"));
			ClassManager::includeClass("xTend\\Blueprints\\BaseModel", $this->_dirBlueprints->file("BaseModel.php"));
			//set post and pre config arrays
			$this->_preConfigMethods = [];
			$this->_postConfigMethods = [];
		}
		//config include
		public function configure() {
			$filemanager = $this->getFileManager();
			$confdir = $this->getConfigDirectory();
			$files = $confdir->files(true);
			//process excludes
			$excludes = array_filter($files, function($file) { return ($file->name()=='.exclude'); });
			foreach($excludes as $exclude) {
				$parent = $exclude->parent();
				$files = array_filter($files, function($file) use ($parent) {
					return (substr($file->parent(), 0, strlen($parent))!=$parent);
				}); }
			//process ignores
			$ignores = array_filter($files, function($file) { return ($file->name()=='.ignore'); });
			foreach($ignores as $ignore) {
				$lines = preg_split("/\\r\\n|\\r|\\n/", $ignore->read());
				$ignore_files = [$ignore]; foreach($lines as $line) { $ignore_files[] = new FileHandler\File($this, $ignore->parent()."/$line"); }
				$files = array_filter($files, function($file) use ($ignore_files) {
					return (array_search($file, $ignore_files)===false);
				});
			}
			//process orders
			$orders = array_filter($files, function($file) { return ($file->Name()=='.order'); });
			foreach($orders as $order) {
				$lines = preg_split("/\\r\\n|\\r|\\n/", $order->read());
				$included_files = [$order];
				foreach($lines as $line) {
					$order_file = new FileHandler\File($this, $order->parent()."/$line");
					if($order_file->exists()) {
						$included_files[] = $order_file;
						$filemanager->includeFile($order_file); }
				}
				//filter out the already included ones
				$files = array_filter($files, function($file) use ($included_files) {
					return (array_search($file, $included_files)===false);
				});
			}
			//include remaining files
			foreach($files as $file) {
				if($file->extension()!="json") $file->include();
			}
		}
		//libraries include
		public function loadLibraries() {
			$filemanager = $this->getFileManager();
			$libsdir = $this->getLibsDirectory();
			$files = $libsdir->files(true);
			//process excludes
			$excludes = array_filter($files, function($file) { return ($file->name()=='.exclude'); });
			foreach($excludes as $exclude) {
				$parent = $exclude->parent();
				$files = array_filter($files, function($file) use ($parent) {
					return (substr($file->parent(), 0, strlen($parent))!=$parent);
				}); }
			//process ignores
			$ignores = array_filter($files, function($file) { return ($file->name()=='.ignore'); });
			foreach($ignores as $ignore) {
				$lines = preg_split("/\\r\\n|\\r|\\n/", $ignore->read());
				$ignore_files = [$ignore]; foreach($lines as $line) { $ignore_files[] = new FileHandler\File($this, $ignore->parent()."/$line"); }
				$files = array_filter($files, function($file) use ($ignore_files) {
					return (array_search($file, $ignore_files)===false);
				});
			}
			//process orders
			$orders = array_filter($files, function($file) { return ($file->Name()=='.order'); });
			foreach($orders as $order) {
				$lines = preg_split("/\\r\\n|\\r|\\n/", $order->read());
				$included_files = [$order];
				foreach($lines as $line) {
					$order_file = new FileHandler\File($this, $order->parent()."/$line");
					if($order_file->exists()) {
						$included_files[] = $order_file;
						$filemanager->includeFile($order_file); }
				}
				//filter out the already included ones
				$files = array_filter($files, function($file) use ($included_files) {
					return (array_search($file, $included_files)===false);
				});
			}
			//include remaining files
			foreach($files as $file) {
				if($file->extension()!="json") $file->include();
			}
		}
		//run function
		public function run() {
			if($this->_inDevelopment) { ini_set('display_errors', 1); }
			//integrity check
			$this->applicationIntegrityCheck();
			//start a session
			SessionHandler::configuration(json_decode($this->_fileHandler->system("Config.Sessions.Sessions.json")->read(), true));
			SessionHandler::start();
			//run library inclusion
			$this->loadLibraries();
			//run preconfig methods
			foreach ($this->_preConfigMethods as $method) { $method($this); }
			//include config
			$this->configure();
			//run post config methhods
			foreach ($this->_postConfigMethods as $method) { $method($this); }
			//create backup if necessary
			$this->_backupManager->create();
			//start the router
			if(!$this->_bootstrapMode) {
				$this->_router->execute();
			}
		}
	}
	/**
		Global functions for initializing and retrieving app instances
	**/
	if(!function_exists("getCurrentApp")) {
		function getCurrentApp($ns) {
			//get system directory
			global $apps;
			if(is_array($apps)&&isset($apps[$ns])) {
				return $apps[$ns];
			} return false;
		}
	}
	if(!function_exists("createNewApp")) {
		function createNewApp($ns, $public_directory, $bootstrap_mode = false) {
			global $apps;
			if(!is_array($apps))
				$apps=[];
			//create new app instance
			$apps[$ns]=new App($ns, $public_directory, $bootstrap_mode);
			return $apps[$ns];
		}
	}
