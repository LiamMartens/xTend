<?php
    namespace xTend\Workbench;
    /**
    * The Command class handles executing and
    * matching a command
    */
    class Command {
        /** @var xTend\Core\App Current application */
        private $_app;
        /** @var string Contains the regex for the command */
        private $_rx;
        /** @var callable Contains the command function */
        private $_call;

        /**
        * @param xTend\Core\App $app
        * @param string $rx
        * @param function $call
        */
        public function __construct($app, $rx, $call) {
            $this->_app = $app;
            $this->_rx = "/$rx/";
            $this->_call = $call;
        }

        /**
        * Checks whether the command is a match
        *
        * @param string reference $command
        *
        * @return boolean
        */
        public function isMatch(&$command) {
            return preg_match($this->_rx, $command);
        }

        /**
        * Executes a command
        *
        * @param array reference $arguments
        *
        * @return mixed
        */
        public function execute(&$arguments) {
            return call_user_func($this->_call, $this->_app, $arguments);
        }
    }
    
    /**
    * The Workbench handles registering
    * and executing commands
    */
    class Workbench {
        /** @var string Contains the application namespace */
        private static $_ns;
        /** @var xTend\Core\App Current application */
        private static $_app;
        /** @var array Contains the command line arguments */
        private static $_argv;
        /** @var xTend\Workbench\Command Contains the current executed command */
        private static $_command;
        /** @var array Contains all registered commands */
        private static $_commands;
        /** @var array Contains the Workbench configuration */
        private static $_configuration;

        /**
        * Generates a key
        *
        * @return string
        */
        public static function generate() {
            return sha1(random_bytes(8));
        }

        /**
        * Sets the workbench namespace
        *
        * @param string $ns
        */
        public static function setNamespace($ns) {
            self::$_ns = $ns;
        }

        /**
        * Sets the workbench application
        *
        * @param xTend\Core\App $app
        */
        public static function setApp($app) {
            self::$_app=$app;
        }

        /**
        * Sets the workbench arguments
        *
        * @param array $argv
        */
        public static function setArgv($argv) {
            self::$_argv = $argv;
            //set command
            self::$_command='';
            $args_count=count(self::$_argv);
            $i=1; while($i<$args_count) {
                if($i>1) self::$_command.=' ';
                self::$_command.=self::$_argv[$i]; ++$i;
            }
        }

        public static function loadConfiguration() {
            self::$_configuration = json_decode(file_get_contents(__DIR__.'/../.workbench'), true);
        }

        public static function saveConfiguration() {
            file_put_contents(__DIR__.'/../.workbench', json_encode(self::$_configuration));
        }

        public static function loadCommands() {
            require_once(__DIR__."/../.commands");
        }

        /**
        * Registers a command
        *
        * @param string $rx
        * @param callable $call
        * @param string $name
        */
        public static function registerCommand($rx, $call, $name = false) {
            if($name===false) {
                self::$_commands[] = new Command(self::$_app, $rx, $call);
            } else { self::$_commands[$name] = new Command(self::$_app, $rx, $call); }
        }

        /**
        * @param mixed $key
        *
        * @return mixed
        */
        public static function getConfiguration($key) {
            if(isset(self::$_configuration[$key])) {
                return self::$_configuration[$key];
            }
            return false;
        }

        /**
        * @return string
        */
        public static function namespaceApplication() {
            return str_replace('\\', '.', self::$_ns);
        }

        /**
        * Checks whether the namespac is a match
        *
        * @return boolean
        */
        public static function namespaceApplicationMatch() {
            return (self::$_configuration["application"]==self::namespaceApplication());
        }

        /**
        * Checks whether the application exists
        *
        * @return boolean
        */
        public static function currentApplicationExists() {
            return is_file(__DIR__.'/../'.self::getConfiguration('application').'/Core/App.php');
        }

        /**
        * Checks whether the public directory exists
        *
        * @return boolean
        */
        public static function currentPublicExists() {
            return is_file(__DIR__.'/../'.self::getConfiguration('public').'/index.php');
        }

        public static function includeApplication() {
            require_once(__DIR__.'/../'.self::getConfiguration('application').'/Core/App.php');
        }

        /**
        * Checks whether an app match
        *
        * @return boolean
        */
        public static function isAppMatch() {
            if(isset(self::getConfiguration('applications')[self::namespaceApplication()])) {
                $rsts = self::getConfiguration('applications')[self::namespaceApplication()];
                $domain_match = (($_SERVER['HTTP_HOST']|$_SERVER['SERVER_NAME']==trim($rsts["url"]))||($rsts["url"]=="*"));
                $request = trim($_SERVER['REQUEST_URI'], '/');
                $path = trim($rsts["path"], '/');
                $path_match = (($path=="*")||
                                ((strlen($request)==strlen($path))&&($request==$path))||
                                (substr($request, 0, strlen($path))==$path));
                return ($domain_match&&$path_match);
            }
            return false;
        }

        /**
        * Runs the workbench
        *
        * @return mixed
        */
        public static function run() {
            foreach(self::$_commands as $command) {
                if($command->isMatch(self::$_command)) {
                    return $command->execute(self::$_argv);
                }
            }
            self::$_commands['help']->execute(self::$_argv);
        }

        /**
        * Adds a new application to the workbench
        *
        * @param string $name
        * @param string $url
        * @param string $path
        *
        * @return boolean
        */
        public static function addApplication($name, $url, $path) {
            if(!isset(self::getConfiguration('applications')[$name])) {
                self::$_configuration['applications'][$name] = [
                    "url" => $url,
                    "path" => $path
                ];
                self::saveConfiguration();
                return true;
            }
            return false;
        }

        /**
        * Removes an application
        *
        * @param string $name
        *
        * @return boolean
        */
        public static function removeApplication($name) {
            if(isset(self::getConfiguration('applications')[$name])) {
                unset(self::$_configuration['applications'][$name]);
                self::saveConfiguration();
                return true;
            }
            return false;
        }

        /**
        * Sets the application
        *
        * @param string $name
        */
        public static function setApplication($name) {
            self::$_configuration['application'] = $name;
            self::saveConfiguration();
        }

        /**
        * Sets the public directory
        *
        * @param string $name
        */
        public static function setPublic($public) {
            self::$_configuration['public'] = $public;
            self::saveConfiguration();
        }
    }
