<?php
	namespace xTend
	{
		class App
		{
			//moved core config from sperate const class to App
			private $_xTendVersion = "0.7.0";
			private $_url = "http://localhost:90";
			private $_inDevelopment = false;
			private $_charset = "UTF-8";
			private $__companyName = "My company";
			private $_language = "en";
			private $_description = "My application's description";
			private $_keywords = "keyword 1, keyword 2";
			private $_author = "Author Name";
			private $_copyright = "2015";
			private $_backupInterval = "1 week";
			private $_backupLimit = 10;
			private $_logLimit = 30;
			private $_namespace = "xTend\\Application";
			//app configuration getters
			public function getVersion() { return $this->_xTendVersion; }
			public function getUrl() { return $this->_url; }
			public function getDevelopmentStatus() { return $this->_inDevelopment; }
			public function getCharset() { return $this->_charset; }
			public function getCompanyName() { return $this->__companyName; }
			public function getLanguage() { return $this->_language; }
			public function getDescription() { return $this->_description; }
			public function getKeywords() { return $this->_keywords; }
			public function getAuthor() { return $this->_author; }
			public function getCopyright() { return $this->_copyright; }
			public function getBackupInterval() { return $this->_backupInterval; }
			public function getBackupLimit() { return $this->_backupLimit; }
			public function getLogLimit() { return $this->_logLimit; }
			public function getNamespace() { return $this->_namespace; }
			/**
				app configuration setters -> if needed to set during runtime (for a cms for exmaple)
				-> not for xTend version.
			**/
			public function setUrl($url) { $this->_url = $url; }
			public function setCharset($chars) { $this->_charset = $chars; }
			public function setDevelopmentStatus($status) { $this->_inDevelopment = $status; }
			public function setCompanyName($name) { $this->__companyName = $name; }
			public function setLanguage($lang) { $this->_language = $lang; }
			public function setDescription($desc) { $this->_description = $desc; }
			public function setKeywords($keyw) { $this->_keywords = $keyw; }
			public function setAuthor($author) { $this->_author = $author; }
			public function setCopyright($notice) { $this->_copyright = $notice; }
			public function setBackupInterval($interval) { $this->_backupInterval = $interval; }
			public function setBackupLimit($limit) { $this->_backupLimit = $limit; }
			public function setLogLimit($limit) { $this->_logLimit = $limit; }
			public function setNamespace($ns) { $this->_namespace=$ns; }
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
			//error code handler
			private $_errorCodeHandler;
			public function getErrorCodeHandler() { return $this->_errorCodeHandler; }
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
			private $_urlHandle;
			public function getUrlHandle() { return $this->_urlHandle; }
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
			//error throw
			public function throwError($code) {
				$error = $this->_errorCodeHandler->findError($code);
				if($error instanceof ErrorCode) {
					$this->_logHandler->write($error, $_SERVER["REQUEST_URI"]."/".$_SERVER["REMOTE_ADDR"]);
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
				//check php version
				if(phpversion()<"5.4")
					die("Your PHP version is lower than 5.4");
				//check directories
				$directories = ["Backups","Controllers","Layouts","Logs","Models","Modules","ViewOutput","Views","Meta"];
				$writable_system_directories = ["Backups","Logs","ViewOutput","Meta"];
				$writable_public_directories = ["css"];
				$can_write = is_writable($this->_dirSystem);
				$integrity_success = true;
				foreach ($directories as $dir) {
					if(!is_dir($this->_dirSystem."/$dir")) {
						if((($can_write)&&(mkdir($this->_dirSystem."/$dir")===false))||(!$can_write)) {
							echo ("Failed to create System directory ".$this->_dirSystem."/$dir<br>"); $integrity_success=false;
						}
					}
				}
				foreach ($writable_system_directories as $dir) {
					if(is_dir($this->_dirSystem."/$dir")&&!is_writable($this->_dirSystem."/$dir")) {
						echo $this->_dirSystem."/$dir is not writable<br>"; $integrity_success=false;
					}
				}
				foreach ($writable_public_directories as $dir) {
					if(is_dir($this->_dirPublic."/$dir")&&!is_writable($this->_dirPublic."/$dir")) {
						echo $this->_dirPublic."/$dir is not writable<br>"; $integrity_success=false;
					}
				}
				if(!$integrity_success)
					die("<br>Integrity check failed");
			}
			//constructor
			public function __construct($ns, $public_directory, $bootstrap_mode = false) {
				$this->_namespace=$ns;
				//the directives are set automatically since they are very important to the application itself
				//set system directory
				$this->_dirSystem = substr(__DIR__,0,strlen(__DIR__)-5);
				//set public directory
				$this->_dirPublic = $public_directory;
				//integrity check
				$this->applicationIntegrityCheck();
				//set content charset
				header("Content-Type:text/html;charset=".$this->_charset);
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
				ClassManager::includeClass("xTend\\SettingsContainer", $this->_dirSystem."/Core/SettingsContainer.php");
				$this->_settingsContainer = new SettingsContainer();
				//include dir class
				ClassManager::includeClass("xTend\\DirectoryHandler", $this->_dirSystem."/Core/DirectoryHandler.php");
				$this->_directoryHandler = new DirectoryHandler($this);
				//include file class
				ClassManager::includeClass("xTend\\FileHandler", $this->_dirSystem."/Core/FileHandler.php");
				$this->_fileHandler = new FileHandler($this);
				//include archive class
				ClassManager::includeClass("xTend\\Archive", $this->_dirSystem."/Core/Archive.php");
				//include ErrorCodeHandler
				ClassManager::includeClass("xTend\\ErrorCodeHandler", $this->_dirSystem."/Core/ErrorCodeHandler.php");
				$this->_errorCodeHandler = new ErrorCodeHandler();
				//include LogHandler
				ClassManager::includeClass("xTend\\LogHandler", $this->_dirSystem."/Core/LogHandler.php");
				$this->_logHandler = new LogHandler($this);
				//include ModelHandler
				ClassManager::includeClass("xTend\\ModelHandler", $this->_dirSystem."/Core/ModelHandler.php");
				$this->_modelHandler = new ModelHandler($this);
				//include ControllerHandler
				ClassManager::includeClass("xTend\\ControllerHandler", $this->_dirSystem."/Core/ControllerHandler.php");
				$this->_controllerHandler = new ControllerHandler($this);
				//Include view blueprints
				ClassManager::includeClass("xTend\\BaseView", $this->_dirSystem."/Blueprints/BaseView.php");
				ClassManager::includeClass("xTend\\BaseDataView", $this->_dirSystem."/Blueprints/BaseDataView.php");
				ClassManager::includeClass("xTend\\View", $this->_dirSystem."/Objects/View.php");
				//include ViewHandler
				ClassManager::includeClass("xTend\\ViewHandler", $this->_dirSystem."/Core/ViewHandler.php");
				$this->_viewHandler = new ViewHandler($this);
				//include UrlHandle
				ClassManager::includeClass("xTend\\BaseDataExtension", $this->_dirSystem."/Blueprints/BaseDataExtension.php");
				ClassManager::includeClass("xTend\\UrlHandle", $this->_dirSystem."/Core/UrlHandle.php");
				$this->_urlHandle = new UrlHandle($this);
				//include Route Object
				ClassManager::includeClass("xTend\\Route", $this->_dirSystem."/Objects/Route.php");
				//include Router
				ClassManager::includeClass("xTend\\Router", $this->_dirSystem."/Core/Router.php");
				$this->_router = new Router($this);
				//include BackupManager
				ClassManager::includeClass("xTend\\BackupManager", $this->_dirSystem."/Core/BackupManager.php");
				$this->_backupManager = new BackupManager($this);
				//include crypto
				ClassManager::includeClass("Defuse\\CryptoLoader", $this->_dirSystem."/Core/Crypto/CryptoLoader.php");
				\Defuse\Crypto\CryptoLoader::load();
				//include SessionHandler class
				ClassManager::includeClass("xTend\\Session", $this->_dirSystem."/Core/Session.php");
				ClassManager::includeClass("xTend\\SessionHandler", $this->_dirSystem."/Core/SessionHandler.php");
				//include Cookies class
				ClassManager::includeClass("xTend\\Cookie", $this->_dirSystem."/Core/Cookie.php");
				//include filemanager class
				ClassManager::includeClass("xTend\\FileManager", $this->_dirSystem."/Core/FileManager.php");
				$this->_fileManager = new FileManager();
				//include SortHelper
				ClassManager::includeClass("xTend\\SortHelper", $this->_dirSystem."/Core/SortHelper.php");
				$this->_sortHelper = new SortHelper();
				//include Wow Compiler
				ClassManager::includeClass("xTend\\Wow", $this->_dirSystem."/Core/Wow.php");
				$this->_wowCompiler = new Wow($this);
				//inlcude Controller and model bluepprints
				ClassManager::includeClass("xTend\\BaseController", $this->_dirSystem."/Blueprints/BaseController.php");
				ClassManager::includeClass("xTend\\BaseDataController", $this->_dirSystem."/Blueprints/BaseDataController.php");
				ClassManager::includeClass("xTend\\BaseModel", $this->_dirSystem."/Blueprints/BaseModel.php");
				//set post and pre config arrays
				$this->_preConfigMethods = [];
				$this->_postConfigMethods = [];
			}
			//config include
			public function configure() {
				//add configuration files
				$files=$this->_directoryHandler->recursiveFiles($this->_dirSystem."/Config");
				$this->_fileManager->includeFiles($files);
			}
			//libraries include
			public function loadLibraries() {
				$directories = $this->_directoryHandler->recursiveDirectories($this->_dirSystem."/Libs"); $directories[] = $this->_dirSystem."/Libs";
				//sort by directory depth
				$this->_sortHelper->sortByNumberOfSlashes($directories);
				//go through directories to see whether they need to be excluded
				$excluded_dirs=[]; $ok_dirs=[];
				foreach ($directories as $dir) {
					if($this->_fileHandler->exists($dir."/.exclude"))
						$excluded_dirs[]=$dir;
					else {
						//check for subdirectory of excluded directory
						$excluded=false;
						foreach ($excluded_dirs as $exd) {
							if(substr($dir, 0, strlen($exd)+1)==$exd."/") { $excluded_dirs[]=$dir; $excluded=true; break; } }
						//passed -> add to ok_dirs;
						if(!$excluded) { $ok_dirs[]=$dir; }
					}
				}
				//go through all directories again now skipping the exluded directories
				foreach ($ok_dirs as $dir) {
					$files=$this->_directoryHandler->files($dir);
					//does ignore file exist
					$ignore_file_pos=array_search(".ignore", $files);
					//if found, read ignore file, unset ignore file from files array and remove all ignored files from the array
					if($ignore_file_pos!==false) {
						$contents=$this->_fileHandler->read("$dir/.ignore"); $contents=explode("\n", str_replace("\r\n", "\n", $contents));
						//unset ignore file itself
						unset($files[$ignore_file_pos]);
						//unset all ignored files
						foreach ($contents as $fi) {
							$fi_pos=array_search($fi, $files);
							if($fi_pos!==false) unset($files[$fi_pos]);
						}
					}
					//does order file exist
					$order_file_pos=array_search(".order", $files);
					//if found read order file, unset order file from files array and include all order files first and unset these from file array
					if($order_file_pos!==false) {
						$contents=$this->_fileHandler->read("$dir/.order"); $contents=explode("\n", str_replace("\r\n", "\n", $contents));
						//unset ordr file itsefl
						unset($files[$order_file_pos]);
						//include all order files and unset
						foreach ($contents as $fo) {
							$fo_pos=array_search($fo, $files);
							if($fo_pos!==false) {
								$this->_fileManager->includeFile("$dir/$fo");
								unset($files[$fo_pos]); }
						}
					}
					//include remaining files
					foreach ($files as $f) {
						$this->_fileManager->includeFile("$dir/$f");
					}
				}
			}
			//run function
			public function run() {
				//create backup if necessary
				$this->_backupManager->create();
				//start a session
				SessionHandler::start();
				//generate the cookie key
				Cookie::generate();

				//run library inclusion
				$this->loadLibraries();
				//run preconfig methods
				foreach ($this->_preConfigMethods as $method) { $method($this); }
				//include config
				$this->configure();
				//run post config methhods
				foreach ($this->_postConfigMethods as $method) { $method($this); }

				//start the router
				if(!$this->_bootstrapMode)
					$this->_router->execute();
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
			} }
	}
