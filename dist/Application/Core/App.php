<?php
    namespace xTend\Core;
    /**
    * The App class contains the starting point
    * of the whole xTend application
    */
    class App
    {
        /** @var string Contains the current xTend version */
        private $_xTendVersion = "0.9.8";
        /** @var string Contains the application's URL */
        private $_url = "http://localhost";
        /** @var boolean Contains the status of development mode */
        private $_inDevelopment = false;
        /** @var string|boolean Contains the backup inteval */
        private $_backupInterval = "1 week";
        /** @var integer Contains the log limit */
        private $_backupLimit = 10;
        /** @var integer Contains the log limit */
        private $_logLimit = 30;
        /** @var string Contains the application's namespace */
        private $_namespace = "Application";
        /**
        * Returns the current xTend version
        *
        * @return string
        */
        public function getVersion() { return $this->_xTendVersion; }
        /**
        * Returns the application's url
        *
        * @return string
        */
        public function getUrl() { return $this->_url; }
        /**
        * Returns the application's development status
        *
        * @return boolean
        */
        public function getDevelopmentStatus() { return $this->_inDevelopment; }
        /**
        * Returns the backup interval
        *
        * @return string
        */
        public function getBackupInterval() { return $this->_backupInterval; }
        /**
        * Returns the limit of number of backups
        *
        * @return integer
        */
        public function getBackupLimit() { return $this->_backupLimit; }
        /**
        * Returns the limit of number of logs
        *
        * @return integer
        */
        public function getLogLimit() { return $this->_logLimit; }
        /**
        * Returns the namespace of the application
        *
        * @return string
        */
        public function getNamespace() { return $this->_namespace; }
        /**
        * Sets the url of the application
        *
        * @param string $url
        */
        public function setUrl($url) { $this->_url = $url; }
        /**
        * Sets the development status of the application
        *
        * @param boolean $status
        */
        public function setDevelopmentStatus($status) { $this->_inDevelopment = $status; }
        /**
        * Sets the interval of backups
        *
        * @param string $interval
        */
        public function setBackupInterval($interval) { $this->_backupInterval = $interval; }
        /**
        * Sets the limit of number of backups
        *
        * @param integer $limit
        */
        public function setBackupLimit($limit) { $this->_backupLimit = $limit; }
        /**
        * Sets the limit of number of logs
        *
        * @param integer $limit
        */
        public function setLogLimit($limit) { $this->_logLimit = $limit; }
        /**
        * Sets application configuration values
        *
        * @param array $confvalues
        */
        public function configuration($confvalues) {
            //set directory settings using an array
            foreach ($confvalues as $key => $value) {
                $f_name = 'set'.$key;
                $this->$f_name($value);
            }
        }

        private $_dirBackups = "Backups";
        /**
        * Sets the backup directory of the application
        *
        * @param string $dir
        */
        public function setBackupsDirectory($dir) { $this->_dirBackups = $this->getDirectoryHandler()->system($dir); }
        /**
        * Gets the current backup directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getBackupsDirectory() { return $this->_dirBackups; }

        private $_dirBlueprints = "Blueprints";
        /**
        * Sets the blueprints directory
        *
        * @param string $dir
        */
        public function setBlueprintsDirectory($dir) { $this->_dirBlueprints = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the current blueprints directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getBlueprintsDirectory() { return $this->_dirBlueprints; }

        private $_dirConfig = "Config";
        /**
        * Sets the application's config directory
        *
        * @param string $dir
        */
        public function setConfigDirectory($dir) { $this->_dirConfig = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's config directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getConfigDirectory() { return $this->_dirConfig; }

        private $_dirControllers = "Controllers";
        /**
        * Sets the application's controler directory
        *
        * @param string $dir
        */
        public function setControllersDirectory($dir) { $this->_dirControllers = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's config directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getControllersDirectory() { return $this->_dirControllers; }

        private $_dirLayouts = "Layouts";
        /**
        * Sets the application's layouts directory
        *
        * @param string $dir
        */
        public function setLayoutsDirectory($dir) { $this->_dirLayouts = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's layouts directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getLayoutsDirectory() { return $this->_dirLayouts; }

        private $_dirLibs = "Libs";
        /**
        * Sets the application's libs directory
        *
        * @param string $dir
        */
        public function setLibsDirectory($dir) { $this->_dirLibs = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's libs directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getLibsDirectory() { return $this->_dirLibs; }

        private $_dirLogs = "Logs";
        /**
        * Sets the application's logs directory
        *
        * @param string $dir
        */
        public function setLogsDirectory($dir) { $this->_dirLogs = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's logs directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getLogsDirectory() { return $this->_dirLogs; }

        private $_dirMeta = "Meta";
        /**
        * Sets the application's meta directory
        *
        * @param string $dir
        */
        public function setMetaDirectory($dir) { $this->_dirMeta = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's meta directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getMetaDirectory() { return $this->_dirMeta; }

        private $_dirModels = "Models";
        /**
        * Sets the application's models directory
        *
        * @param string $dir
        */
        public function setModelsDirectory($dir) { $this->_dirModels = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's models directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getModelsDirectory() { return $this->_dirModels; }

        private $_dirModules = "Modules";
        /**
        * Sets the application's modules directory
        *
        * @param string $dir
        */
        public function setModulesDirectory($dir) { $this->_dirModules = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's modules directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getModulesDirectory() { return $this->_dirModules; }

        private $_dirObjects = "Objects";
        /**
        * Sets the application's objects directory
        *
        * @param string $dir
        */
        public function setObjectsDirectory($dir) { $this->_dirObjects = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's objects directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getObjectsDirectory() { return $this->_dirObjects; }

        private $_dirViewOutput = "ViewOutput";
        /**
        * Sets the application's ViewOutput directory
        *
        * @param string $dir
        */
        public function setViewOutputDirectory($dir) { $this->_dirViewOutput = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's ViewOutput directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getViewOutputDirectory() { return $this->_dirViewOutput; }

        private $_dirViews = "Views";
        /**
        * Sets the application's views directory
        *
        * @param string $dir
        */
        public function setViewsDirectory($dir) { $this->_dirViews = $this->getDirectoryHandler()->system($dir); }
        /**
        * Returns the application's views directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getViewsDirectory() { return $this->_dirViews; }

        /**
        * Set directory settings using an array
        *
        * @param array $dirvalues
        */
        public function directories($dirvalues) {
            foreach ($dirvalues as $dir => $value) {
                $f_name = 'set'.$dir.'Directory';
                $this->$f_name($value);
            }
        }

        private $_dirSystem;
        private $_dirPublic;

        /**
        * Returns the current application directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getSystemDirectory() { return $this->_dirSystem;}
        /**
        * Returns the current public directory
        *
        * @return xTend\Core\DirectoryHandler\Directory
        */
        public function getPublicDirectory() { return $this->_dirPublic;}

        private $_bootstrapMode;
        /**
        * Returns the status of the bootstrap mode
        *
        * @return boolean
        */
        public function getBootstrapMode() { return $this->_bootstrapMode; }

        private $_settingsContainer;
        /**
        * Returns the application's SettingsContainer
        *
        * @return xTend\Core\SettingsContainer
        */
        public function getSettingsContainer() { return $this->_settingsContainer; }

        private $_fileHandler;
        /**
        * Returns the application's FileHandler
        *
        * @return xTend\Core\FileHandler
        */
        public function getFileHandler() { return $this->_fileHandler; }

        private $_directoryHandler;
        /**
        * Returns the application's DirectoryHandler
        *
        * @return xTend\Core\DirectoryHandler
        */
        public function getDirectoryHandler() { return $this->_directoryHandler; }

        private $_statusCodeHandler;
        /**
        * Returns the application's StatusCodeHandler
        *
        * @return xTend\Core\StatusCodeHandler
        */
        public function getStatusCodeHandler() { return $this->_statusCodeHandler; }

        private $_logHandler;
        /**
        * Returns the application's LogHandler
        *
        * @return xTend\Core\LogHandler
        */
        public function getLogHandler() { return $this->_logHandler; }

        private $_modelHandler;
        /**
        * Returns the application's ModelHandler
        *
        * @return xTend\Core\ModelHandler
        */
        public function getModelHandler() { return $this->_modelHandler; }

        private $_controllerHandler;
        /**
        * Returns the application's ControllerHandler
        *
        * @return xTend\Core\ControllerHandler
        */
        public function getControllerHandler() { return $this->_controllerHandler; }

        private $_viewHandler;
        /**
        * Returns the application's ViewHandler
        *
        * @return xTend\Core\ViewHandler
        */
        public function getViewHandler() { return $this->_viewHandler; }

        private $_UrlHandler;
        /**
        * Returns the application's UrlHandler
        *
        * @return xTend\Core\UrlHandler
        */
        public function getUrlHandler() { return $this->_UrlHandler; }

        private $_router;
        /**
        * Returns the application's Router
        *
        * @return xTend\Core\Router
        */
        public function getRouter() { return $this->_router; }

        private $_backupManager;
        /**
        * Returns the application's BackupManager
        *
        * @return xTend\Core\BackupManager
        */
        public function getBackupManager() { return $this->_backupManager; }

        private $_fileManager;
        /**
        * Returns the application's FileManager
        *
        * @return xTend\Core\FileManager
        */
        public function getFileManager() { return $this->_fileManager; }

        private $_sortHelper;
        /**
        * Returns the application's SortHelper
        *
        * @return xTend\Core\SortHelper
        */
        public function getSortHelper() { return $this->_sortHelper; }

        private $_wowCompiler;
        /**
        * Returns the application's Wow templating engine
        *
        * @return xTend\Core\Wow
        */
        public function getWowCompiler() { return $this->_wowCompiler; }

        private $_requestDataHandler;
        /**
        * Returns the application's RequestDataHandler
        *
        * @return xTend\Core\RequestDataHandler
        */
        public function getRequestDataHandler() { return $this->_requestDataHandler; }

        private $_htmlHandler;
        /**
        * Returns the application's HTMLHandler
        *
        * @return xTend\Core\HTMLHandler
        */
        public function getHTMLHandler() { return $this->_htmlHandler; }

        private $_formTokenHandler;
        /**
        * Returns the application's FormTokenHandler
        *
        * @return xTend\Core\FormTokenHandler
        */
        public function getFormTokenHandler() { return $this->_formTokenHandler; }

        private $_packagistHandler;
        /**
        * Returns the application's PackagistHandler
        *
        * @return xTend\Core\PackagistHandler
        */
        public function getPackagistHandler() { return $this->_packagistHandler; }

        private $_requestHandler;
        /**
        * Returns the application's RequestHandler
        *
        * @return xTend\Core\RequestHandler
        */
        public function getRequestHandler() { return $this->_requestHandler; }

        /**
        * Throws an application error and sets an HTTP code
        *
        * @param integer $code
        *
        * @return boolean
        */
        public function throwError($code) {
            header("HTTP/1.0 $code");
            $error = $this->_statusCodeHandler->findStatus($code);
            if($error instanceof StatusCode) {
                $this->_logHandler->write($error, $_SERVER["REQUEST_URI"]."\t".$_SERVER["REMOTE_ADDR"]);
                return $this->_router->throwError($code);
            }
            return false;
        }

        private $_preConfigMethods;
        /**
        * Adds a pre configuration method
        *
        * @param function $fn
        */
        public function addPreconfigurationMethod($fn) {$this->_preConfigMethods[]=$fn; }

        private $_postConfigMethods;
        /**
        * Adds a post configuration method
        *
        * @param function $fn
        */
        public function addPostConfigurationMethod($fn) {$this->_postConfigMethods[]=$fn; }

        /**
        * Runs directory checks and PHP version
        */
        private function applicationIntegrityCheck() {
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

        /**
        * @param string $ns
        * @param string $public_directory
        * @param boolean $bootstrap_mode
        */
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
            ClassManager::includeClasses([
                [ "xTend\\Core\\SettingsContainer", $this->_dirSystem."/Core/SettingsContainer.php" ],
                [ "xTend\\Core\\Archive", $this->_dirSystem."/Core/Archive.php" ],
                [ "xTend\\Core\\StatusCodeHandler", $this->_dirSystem."/Core/StatusCodeHandler.php" ],
                [ "xTend\\Core\\FileHandler", $this->_dirSystem."/Core/FileHandler.php" ],
                [ "xTend\\Core\\DirectoryHandler", $this->_dirSystem."/Core/DirectoryHandler.php" ]
            ]);
            $this->_settingsContainer = new SettingsContainer();
            $this->_statusCodeHandler = new StatusCodeHandler();
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
            ClassManager::includeClasses([
                [ "xTend\\Core\\LogHandler", $this->_dirSystem."/Core/LogHandler.php" ],
                [ "xTend\\Core\\ModelHandler", $this->_dirSystem."/Core/ModelHandler.php" ],
                [ "xTend\\Core\\ControllerHandler", $this->_dirSystem."/Core/ControllerHandler.php" ],
                [ "xTend\\Blueprints\\BaseView", $this->_dirBlueprints->file("BaseView.php") ],
                [ "xTend\\Blueprints\\BaseDataView", $this->_dirBlueprints->file("BaseDataView.php") ],
                [ "xTend\\Objects\\View",  $this->_dirObjects->file("View.php") ],
                [ "xTend\\Core\\ViewHandler", $this->_dirSystem."/Core/ViewHandler.php" ],
                [ "xTend\\Blueprints\\BaseDataExtension", $this->_dirBlueprints->file("BaseDataExtension.php") ],
                [ "xTend\\Core\\UrlHandler", $this->_dirSystem."/Core/UrlHandler.php" ],
                [ "xTend\\Objects\\Route", $this->_dirObjects->file("Route.php") ],
                [ "xTend\\Core\\Router", $this->_dirSystem."/Core/Router.php" ],
                [ "xTend\\Core\\BackupManager", $this->_dirSystem."/Core/BackupManager.php" ],
                [ "Defuse\\Crypto\\CryptoLoader", $this->_dirSystem."/Core/Crypto/CryptoLoader.php" ],
                [ "xTend\\Core\\Session", $this->_dirSystem."/Core/Session.php" ],
                [ "xTend\\Core\\SessionHandler", $this->_dirSystem."/Core/SessionHandler.php" ],
                [ "xTend\\Core\\Cookie", $this->_dirSystem."/Core/Cookie.php" ],
                [ "xTend\\Core\\FileManager", $this->_dirSystem."/Core/FileManager.php" ],
                [ "xTend\\Core\\SortHelper", $this->_dirSystem."/Core/SortHelper.php" ],
                [ "xTend\\Core\\Wow", $this->_dirSystem."/Core/Wow.php" ],
                [ "xTend\\Core\\RequestDataHandler", $this->_dirSystem."/Core/RequestDataHandler.php" ],
                [ "xTend\\Core\\HTMLHandler", $this->_dirSystem."/Core/HTMLHandler.php" ],
                [ "xTend\\Core\\FormTokenHandler", $this->_dirSystem."/Core/FormTokenHandler.php" ],
                [ "xTend\\Core\\VersionCheck", $this->_dirSystem."/Core/VersionCheck.php" ],
                [ "xTend\\Core\\PackagistHandler", $this->_dirSystem."/Core/PackagistHandler.php" ],
                [ "xTend\\Core\\RequestHandler", $this->_dirSystem."/Core/RequestHandler.php" ],
                [ "xTend\\Blueprints\\BaseController", $this->_dirBlueprints->file("BaseController.php") ],
                [ "xTend\\Blueprints\\BaseDataController", $this->_dirBlueprints->file("BaseDataController.php") ],
                [ "xTend\\Blueprints\\BaseModel", $this->_dirBlueprints->file("BaseModel.php") ],
                [ "xTend\\Blueprints\\BaseRespondController", $this->_dirBlueprints->file("BaseRespondController.php") ],
                [ "xTend\\Objects\\Request", $this->_dirObjects->file("Request.php") ]
            ]);
            $this->_fileHandler = new FileHandler($this);
            $this->_logHandler = new LogHandler($this);
            $this->_modelHandler = new ModelHandler($this);
            $this->_controllerHandler = new ControllerHandler($this);
            $this->_viewHandler = new ViewHandler($this);
            $this->_UrlHandler = new UrlHandler($this);
            $this->_router = new Router($this);
            $this->_backupManager = new BackupManager($this);
            \Defuse\Crypto\CryptoLoader::load();
            $this->_fileManager = new FileManager();
            $this->_sortHelper = new SortHelper();
            $this->_wowCompiler = new Wow($this);
            $this->_requestDataHandler = new RequestDataHandler($this);
            $this->_htmlHandler = new HTMLHandler($this);
            $this->_formTokenHandler = new FormTokenHandler($this);
            $this->_packagistHandler = new PackagistHandler($this);
            $this->_requestHandler = new RequestHandler($this);
            //set post and pre config arrays
            $this->_preConfigMethods = [];
            $this->_postConfigMethods = [];
        }

        /**
        * Includes configuration files
        */
        public function configure() {
            $filemanager = $this->getFileManager();
            $confdir = $this->getConfigDirectory();
            $files = $confdir->files(true);
            //check .exclude files
            $excludes=[]; foreach($files as $key => $file) {
                if($file->name()=='.exclude') {
                    $excludes[]=$file;
                    unset($files[$key]);
                }
            }
            foreach($excludes as $exclude) {
                $parent = $exclude->parent();
                foreach($files as $key => $file) {
                    if(substr($file->parent(), 0, strlen($parent))==$parent) {
                        unset($files[$key]);
                    }
                }
            }
            //check .ignore files
            $ignores=[]; foreach($files as $key => $file) {
                if($file->name()=='.ignore') {
                    $ignores[]=$file;
                    unset($files[$key]);
                }
            }
            foreach($ignores as $ignore) {
                $lines = preg_split("/\\r\\n|\\r|\\n/", $ignore->read());
                $ignore_files = [$ignore]; foreach($lines as $line) { $ignore_files[] = new FileHandler\File($this, $ignore->parent()."/$line"); }
                foreach($files as $key => $file) {
                    if(array_search($file, $ignore_files)!==false) {
                        unset($files[$key]);
                    }
                }
            }
            //check .order files
            $orders=[]; foreach($files as $key => $file) {
                if($file->name()=='.order') {
                    $orders[]=$file;
                    unset($files[$key]);
                }
            }
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
                foreach($files as $key => $file) {
                    if(array_search($file, $included_files)!==false) {
                        unset($files[$key]);
                    }
                }
            }
            //include remaining files
            foreach($files as $file) {
                if($file->extension()=='php') { $file->include(); }
            }
        }

        /**
        * Includes library files
        */
        public function loadLibraries() {
            $filemanager = $this->getFileManager();
            $libsdir = $this->getLibsDirectory();
            $files = $libsdir->files(true);
            //check .exclude files
            $excludes=[]; foreach($files as $key => $file) {
                if($file->name()=='.exclude') {
                    $excludes[]=$file;
                    unset($files[$key]);
                }
            }
            foreach($excludes as $exclude) {
                $parent = $exclude->parent();
                foreach($files as $key => $file) {
                    if(substr($file->parent(), 0, strlen($parent))==$parent) {
                        unset($files[$key]);
                    }
                }
            }
            //check .ignore files
            $ignores=[]; foreach($files as $key => $file) {
                if($file->name()=='.ignore') {
                    $ignores[]=$file;
                    unset($files[$key]);
                }
            }
            foreach($ignores as $ignore) {
                $lines = preg_split("/\\r\\n|\\r|\\n/", $ignore->read());
                $ignore_files = [$ignore]; foreach($lines as $line) { $ignore_files[] = new FileHandler\File($this, $ignore->parent()."/$line"); }
                foreach($files as $key => $file) {
                    if(array_search($file, $ignore_files)!==false) {
                        unset($files[$key]);
                    }
                }
            }
            //check .order files
            $orders=[]; foreach($files as $key => $file) {
                if($file->name()=='.order') {
                    $orders[]=$file;
                    unset($files[$key]);
                }
            }
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
                foreach($files as $key => $file) {
                    if(array_search($file, $included_files)!==false) {
                        unset($files[$key]);
                    }
                }
            }
            //include remaining files
            foreach($files as $file) {
                if($file->extension()=='php') { $file->include(); }
            }
        }

        /**
        * Runs the whole application
        */
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

    if(!function_exists("getCurrentApp")) {
        /**
        * Returns an application by namespace
        *
        * @param string $ns
        *
        * @return xTend\Core\App | boolean
        */
        function getCurrentApp($ns) {
            //get system directory
            global $apps;
            if(is_array($apps)&&isset($apps[$ns])) {
                return $apps[$ns];
            } return false;
        }
    }
    if(!function_exists("createNewApp")) {
        /**
        * Creates a new application by namespace
        *
        * @param string $ns
        * @param string $public_directory
        * @param boolean $bootstrap_mode
        *
        * @return xTend\Core\App
        */
        function createNewApp($ns, $public_directory, $bootstrap_mode = false) {
            global $apps;
            if(!is_array($apps))
                $apps=[];
            //create new app instance
            $apps[$ns]=new App($ns, $public_directory, $bootstrap_mode);
            return $apps[$ns];
        }
    }
