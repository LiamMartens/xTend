<?php
    namespace Application\Core;
    use Application\Objects\StatusCodeHandler\StatusCode;
    use Application\Objects\Router\Route;
    use Application\Objects\DirectoryHandler\Directory;
    use Application\Objects\FileHandler\File;

    /**
    * The App class contains the starting point
    * of the whole xTend application
    */
    class App {
        //
        //
        // GENERAL CONFIGURATION VARIABLES
        //
        //
        /** @var string Contains the current xTend version */
        private static $_xTendVersion = '2.0.0';
        /** @var string Contains the path where xTend is hosted, can be absolute or relative */
        private static $_location = '/'; // private $_location = 'http://localhost/' is also possible
        /** @var string Contains the timezone locale */
        private static $_timezone = 'UTC';
        /** @var string Contains the environment (production, staging, development, ...) */
        private static $_environment = 'production'; // if set to development or dev display_errors will be set on
        /** @var string Contains the backup interval */
        private static $_backupInterval = '1 week';
        /** @var int Contains the limit of backup files */
        private static $_backupLimit = 10;
        /** @var int Contains the limit of logs files */
        private static $_logLimit = 30;
        /** @var boolean Contains the bootstrap mode status */
        private static $_bootstrap = false;
        /** @var string Contains the namespace of the application
        * Used to divide applications and use more than one at
        * the same time if necessary
        */
        private static $_namespace = 'Application';

        /**
        * Returns the xTend version
        * !! No need to override the version -> no setter
        *
        * @return string
        */
        public static function version() { return self::$_xTendVersion; }

        /**
        * Returns or sets the application location
        *
        * @param string:optional $location
        *
        * @return string
        */
        public static function location($location=null) {
            if($location!==null) {
                self::$_location=$location;
            }
            return self::$_location;
        }

        /**
        * Returns or sets the application timezone variable
        *
        * @param string:optional $zone
        *
        * @return string
        */
        public static function timezone($zone=null) {
            if($zone!==null) {
                self::$_timezone = $zone;
                date_default_timezone_set($zone);
            }
            return self::$_timezone;
        }

        /**
        * Returns or sets the application environment
        *
        * @param string:optional $env
        *
        * @return string
        */
        public static function environment($env=null) {
            if($env!==null) {
                self::$_environment=$env;
            }
            return self::$_environment;
        }

        /**
        * Returns or sets the application backup interval
        *
        * @param string|false:optional $interval
        *
        * @return string|false
        */
        public static function backupInterval($interval=null) {
            if($interval!==null) {
                self::$_backupInterval=$interval;
            }
            return self::$_backupInterval;
        }

        /**
        * Returns or sets the application backup limit
        *
        * @param int:optional $limit
        *
        * @return int
        */
        public static function backupLimit($limit=null) {
            if($limit!==null) {
                self::$_backupLimit=$limit;
            }
            return self::$_backupLimit;
        }

        /**
        * Returns or sets the application log limit
        *
        * @param int:optional $limit
        *
        * @return int
        */
        public static function logLimit($limit=null) {
            if($limit!==null) {
                self::$_logLimit=$limit;
            }
            return self::$_logLimit;
        }

        /**
        * Returns or sets the bootstrap mode
        *
        * @param boolean:optional $mode
        *
        * @return boolean
        */
        public static function bootstrap($mode=null) {
            if($mode!==null) {
                self::$_bootstrap=$mode;
            }
            return self::$_bootstrap;
        }

        /**
        * Returns the namespace
        * !! No need to set the namespace outside of the App itself
        *
        * @return string
        */
        public static function namespace() {
            return self::$_namespace;
        }

        //
        //
        // DIRECTORY CONFIGURATION VARIABLES (RELATIVE TO APP DIR)
        //
        //
        /** @var string Contains the system or application directory */
        private static $_directorySystem = 'Application';
        /** @var string Contains the public directory */
        private static $_directoryPublic = 'www';
        /** @var string Contains the directory path where backups are stored */
        private static $_directoryBackups = 'Backups';
        /** @var string Contains the directory path where the blueprints are stored */
        private static $_directoryBlueprints = 'Blueprints';
        /** @var string Contains the directory path where the configuration files are stored */
        private static $_directoryConfig = 'Config';
        /** @var string Contains the directory path where the controllers are stored */
        private static $_directoryControllers = 'Controllers';
        /** @var string Contains the directory path where the layouts are stored */
        private static $_directoryLayouts = 'Layouts';
        /** @var string Contains the directory path where the libs are stored */
        private static $_directoryLibs = 'Libs';
        /** @var string Contains the directory path where the logs are stored */
        private static $_directoryLogs = 'Logs';
        /** @var string Contains the directory path where the meta files are stored */
        private static $_directoryMeta = 'Meta';
        /** @var string Contains the directory path where the models are stored */
        private static $_directoryModels = 'Models';
        /** @var string Contains the directory path where the Modules are stored */
        private static $_directoryModules = 'Modules';
        /** @var string Contains the directory path where the ViewOutput is stored */
        private static $_directoryViewOutput = 'ViewOutput';
        /** @var string Contains the directory path where the views are stored */
        private static $_directoryViews = 'Views';
        /** @var string Contains the directory where the Objects are stored */
        private static $_directoryObjects = 'Objects';
        
        
        /**
        * Returns the application directory
        *
        * @return Directory|string
        */
        public static function system() { return self::$_directorySystem; }

        /**
        * Returns the public directory
        *
        * @return Directory|string
        */
        public static function public() { return self::$_directoryPublic; }

        //
        //
        // NEVER SET DIRECTORIES AFTER APP RUN
        //
        //
        /**
        * Gets or sets the backups directory
        *
        * @return Directory|string
        */
        public static function backups($value=null) {
            if($value!==null) {
                self::$_directoryBackups=$value;
            }
            return self::$_directoryBackups;
        }

        /**
        * Gets or sets the blueprints directory
        *
        * @return Directory|string
        */
        public static function blueprints($value=null) {
            if($value!==null) {
                self::$_directoryBlueprints=$value;
            }
            return self::$_directoryBlueprints;
        }

        /**
        * Returns the config directory
        * just like the Core directory the config directory shouldn't be changed
        *
        * @return Directory|string
        */
        public static function config() { return self::$_directoryConfig; }

        /**
        * Gets or sets the controllers directory
        *
        * @return Directory|string
        */
        public static function controllers($value=null) {
            if($value!==null) {
                self::$_directoryControllers=$value;
            }
            return self::$_directoryControllers;
        }

        /**
        * Gets or sets the layouts directory
        *
        * @return Directory|string
        */
        public static function layouts($value=null) {
            if($value!==null) {
                self::$_directoryLayouts=$value;
            }
            return self::$_directoryLayouts;
        }
        
        /**
        * Gets or sets the libs directory
        *
        * @return Directory|string
        */
        public static function libs($value=null) { 
            if($value!==null) {
                self::$_directoryLibs=$value;
            }
            return self::$_directoryLibs;
        }

        /**
        * Gets or sets the logs directory
        *
        * @return Directory|string
        */
        public static function logs($value=null) { 
            if($value!==null) {
                self::$_directoryLogs=$value;
            }
            return self::$_directoryLogs;
        }

        /**
        * Gets or sets the meta directory
        *
        * @return Directory|string
        */
        public static function meta($value=null) { 
            if($value!==null) {
                self::$_directoryMeta=$value;
            }
            return self::$_directoryMeta;
        }

        /**
        * Gets or sets the models directory
        *
        * @return Directory|string
        */
        public static function models($value=null) {
            if($value!==null) {
                self::$_directoryModels=$value;
            }
            return self::$_directoryModels;
        }

        /**
        * Gets or sets the modules directory
        *
        * @return Directory|string
        */
        public static function modules($value=null) {
            if($value!==null) {
                self::$_directoryModules=$value;
            }
            return self::$_directoryModules;
        }

        /**
        * Gets or sets the views directory
        *
        * @return Directory|string
        */
        public static function views($value=null) {
            if($value!==null) {
                self::$_directoryViews=$value;
            }
            return self::$_directoryViews;
        }

        /**
        * Gets or sets the viewoutput directory
        *
        * @return Directory|string
        */
        public static function viewOutput($value=null) {
            if($value!==null) {
                self::$_directoryViewOutput=$value;
            }
            return self::$_directoryViewOutput;
        }

        /**
        * Gets or sets the objects directory
        *
        * @return Directory|string
        */
        public static function objects($value=null) {
            if($value!==null) {
                self::$_directoryObjects=$value;
            }
            return self::$_directoryObjects;
        }

        /**
        * Sets configuration using an array of variables (directories / config variables)
        * only set directories differently if you know what you are doing
        *
        * @param array $values
        */
        public static function configuration($values) {
            foreach($values as $key => $value) {
                self::$key($value);
            }
        }

        /**
        * Throws an application error and sets an HTTP code
        *
        * @param integer $code
        *
        * @return boolean
        */
        public static function throw($code) {
            header("HTTP/1.0 $code");
            $error = StatusCodeHandler::find($code);
            if($error instanceof StatusCode) {
                LogHandler::write($error, Request::path()."\t".$_SERVER["REMOTE_ADDR"]);
                return Router::throw($code);
            }
            return false;
        }

        /**
        * @param xTend\Objects\Route|string $route
        * @param array $parameters
        * @param array $data
        *
        * @return boolean
        */
        public static function to($route, $parameters = [], $data = []) {
            Session::set('xt-data', json_encode($data));
            $handle='';
            if(is_string($route)) {
                //by route name
                $handle=Router::alias($route)->handle();
            } elseif(($route instanceof Route)&&is_string($route->handle())) {
                //by route object
                $handle=$route->handle();
            }
            $url = ''; $parts = explode('/', $handle);
            foreach ($parts as $part) {
                $match=[];
                if(preg_match("/^(rx)(\{)([a-zA-Z0-9_]+)(\})(\{)(.*)(\})$/", $part, $match)) {
                    if(isset($parameters[$match[3]])) {
                        $url.='/'.$parameters[$match[3]];
                    }
                } elseif(preg_match("/^(\{)([a-zA-Z0-9_]+)(\})$/", $part, $match)) {
                    if(isset($parameters[$match[2]])) {
                        $url.='/'.$parameters[$match[2]];
                    }
                } else { $url.="/$part"; }
            }
            header('Location: '.Request::url().App::location().'/'.$url);
            return true;
        }

        /**
        * @param xTend\Objects\Route|string $route
        * @param array $data
        * @param boolean $inc_url
        */
        public static function navigate($request, $data = [], $inc_url = true) {
            //set temp data and time to live
            Session::set('xt-data', json_encode($data));
            $host=Request::url();
            if(is_string($request)) {
                header('Location: '.(($inc_url) ? ($host.'/') : '').$request);
            } elseif(($request instanceof Route)&&is_string($request->handle())) {
                header('Location: '.$host.'/'.$request->handle()); }
        }

        /**
        * Initiates the App (configure should be called before start)
        *
        * @param string $public Public directory
        * @param boolean $bootstrap Bootstrap mode on|off
        */
        public static function start($public, $bootstrap=false) {
            // check server variables and put temp ones in 
            // if none are present when in cli mode
            if(php_sapi_name()==='cli') {
                $_SERVER['HTTP_USER_AGENT']=sha1(uniqid().microtime());
                $_SERVER['REMOTE_ADDR']=sha1(uniqid().microtime());
                $_SERVER['REQUEST_URI']=sha1(uniqid().microtime());
            }

            // Set default timezone (UTC by default)
            self::timezone(self::$_timezone);

            // set the application namespace using the namespace
            // in the file
            self::$_namespace = substr(__NAMESPACE__, 0, strpos(__NAMESPACE__, '\\'));

            // Set system and public directories
            self::$_directorySystem = '/'.trim(substr(__DIR__, 0, strlen(__DIR__) - 5), '/');
            self::$_directoryPublic = $public;

            // Set bootstrap mode
            self::$_bootstrap = $bootstrap;

            // Include FileManager
            require(self::$_directorySystem.'/Core/FileManager.php');
            // Include other class files using the classmanager
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryObjects.'/StatusCode.php');
            FileManager::include(self::$_directorySystem.'/Core/StatusCodeHandler.php');
            // Include Crypto, SessionHandler, Session and Cookie
            FileManager::include(self::$_directorySystem.'/Core/Crypto/CryptoLoader.php');
            FileManager::include(self::$_directorySystem.'/Core/SessionHandler.php');
            FileManager::include(self::$_directorySystem.'/Core/Session.php');
            FileManager::include(self::$_directorySystem.'/Core/Cookie.php');
            // Include file and directory handlers
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryObjects.'/File.php');
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryObjects.'/Directory.php');
            FileManager::include(self::$_directorySystem.'/Core/FileHandler.php');
            FileManager::include(self::$_directorySystem.'/Core/DirectoryHandler.php');
            // Include Archive and BackupManager
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryObjects.'/Archive.php');
            FileManager::include(self::$_directorySystem.'/Core/BackupManager.php');
            // Include LogHandler
            FileManager::include(self::$_directorySystem.'/Core/LogHandler.php');
            // Include ModelHandler
            FileManager::include(self::$_directorySystem.'/Core/ModelHandler.php');
            // Include ControllerHandler
            FileManager::include(self::$_directorySystem.'/Core/ControllerHandler.php');
            // Include WOW
            FileManager::include(self::$_directorySystem.'/Core/Wow.php');
            // Include data ext view and view handler
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryBlueprints.'/StaticDataExtension.php');
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryBlueprints.'/DataExtension.php');
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryObjects.'/View.php');
            FileManager::include(self::$_directorySystem.'/Core/ViewHandler.php');
            // Include HTML stuff
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryObjects.'/HTMLHandler.php');
            FileManager::include(self::$_directorySystem.'/Core/HTMLHandler.php');
            // Include the Request
            FileManager::include(self::$_directorySystem.'/Core/Request.php');
            // Include FormTokenHandler
            FileManager::include(self::$_directorySystem.'/Core/FormTokenHandler.php');
            // Include VersionCheck
            FileManager::include(self::$_directorySystem.'/Core/VersionCheck.php');
            // Include PackagistHandler
            FileManager::include(self::$_directorySystem.'/Core/PackagistHandler.php');
            // Include ORM
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryObjects.'/xORM.php');
            FileManager::include(self::$_directorySystem.'/Core/xORM.php');
            // Include other blueprints
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryBlueprints.'/Controller.php');
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryBlueprints.'/RespondController.php');
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryBlueprints.'/Model.php');
            // Include Router and Route object if not in bootstrap mode
            FileManager::include(self::$_directorySystem.'/'.self::$_directoryObjects.'/Route.php');
            FileManager::include(self::$_directorySystem.'/Core/Router.php');
        }

        /**
        * Includes configuration files
        */
        public static function configure() {
            $confdir = self::config();
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
                $ignore_files = [$ignore]; foreach($lines as $line) { $ignore_files[] = new File($ignore->parent()."/$line"); }
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
                    $order_file = new File($order->parent()."/$line");
                    if($order_file->exists()) {
                        $included_files[] = $order_file;
                        FileManager::include($order_file); }
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
        public static function libraries() {
            $libsdir = self::libs();
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
                $ignore_files = [$ignore]; foreach($lines as $line) { $ignore_files[] = new File($ignore->parent()."/$line"); }
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
                    $order_file = new File($order->parent()."/$line");
                    if($order_file->exists()) {
                        $included_files[] = $order_file;
                        FileManager::include($order_file); }
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

        private static function integrity() {
            //check php version
            if (version_compare(phpversion(), '7.0.0', '<')) {
                // For using the random_bytes
                die("You need PHP 7 to use xTend");
            }
            //check directories
            $directories = [self::$_directoryBackups,
                            self::$_directoryBlueprints,
                            self::$_directoryConfig,
                            self::$_directoryControllers,
                            self::$_directoryLayouts,
                            self::$_directoryLogs,
                            self::$_directoryModels,
                            self::$_directoryModules,
                            self::$_directoryObjects,
                            self::$_directoryViewOutput,
                            self::$_directoryViews,
                            self::$_directoryMeta,
                            self::$_directoryMeta];
            $writable_system_directories = [
                self::$_directoryViewOutput,
                self::$_directoryMeta
            ];
            if((self::$_backupInterval!==false)||(self::$_backupLimit>0)) { $writable_system_directories[]=self::$_directoryBackups; }
            if(self::$_logLimit>0) { $writable_system_directories[]=self::$_directoryLogs; }
            $can_write = is_writable(self::$_directorySystem);
            $integrity_success = true;
            foreach ($directories as $dir) {
                if(!$dir->exists()) {
                    if((($can_write)&&($dir->create()===false))||(!$can_write)) {
                        echo ("Failed to create System directory ".$dir."<br>"); $integrity_success=false;
                    }
                }
            }
            foreach ($writable_system_directories as $dir) {
                if($dir->exists()&&!$dir->writable()) {
                    echo $dir." is not writable<br>"; $integrity_success=false;
                }
            }
            if(!$integrity_success) {
                die("<br>Integrity check failed");
            }
        }

        /**
        * Runs the app
        */
        public static function run() {
            // set display errors
            // upon development environment
            if((self::$_environment=='development')||(self::$_environment=='dev')) {
                ini_set('display_errors', 1);
            }
            // Set directories
            self::$_directoryBackups = new Directory(self::$_directorySystem.'/'.self::$_directoryBackups);
            self::$_directoryBlueprints = new Directory(self::$_directorySystem.'/'.self::$_directoryBlueprints);
            self::$_directoryConfig = new Directory(self::$_directorySystem.'/'.self::$_directoryConfig);
            self::$_directoryControllers = new Directory(self::$_directorySystem.'/'.self::$_directoryControllers);
            self::$_directoryLayouts = new Directory(self::$_directorySystem.'/'.self::$_directoryLayouts);
            self::$_directoryLibs = new Directory(self::$_directorySystem.'/'.self::$_directoryLibs);
            self::$_directoryLogs = new Directory(self::$_directorySystem.'/'.self::$_directoryLogs);
            self::$_directoryMeta = new Directory(self::$_directorySystem.'/'.self::$_directoryMeta);
            self::$_directoryModels = new Directory(self::$_directorySystem.'/'.self::$_directoryModels);
            self::$_directoryModules = new Directory(self::$_directorySystem.'/'.self::$_directoryModules);
            self::$_directoryViewOutput = new Directory(self::$_directorySystem.'/'.self::$_directoryViewOutput);
            self::$_directoryViews = new Directory(self::$_directorySystem.'/'.self::$_directoryViews);
            self::$_directoryObjects = new Directory(self::$_directorySystem.'/'.self::$_directoryObjects);
            self::$_directorySystem = new Directory(self::$_directorySystem);
            self::$_directoryPublic = new Directory(self::$_directoryPublic);
            // run
            self::integrity();
            SessionHandler::start();
            PackagistHandler::start();
            Request::start();
            self::libraries();
            self::configure();
            BackupManager::create();
            if(self::$_bootstrap!==true) {
                Router::start();
            }
        }
    }