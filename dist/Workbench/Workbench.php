<?php
    namespace xTend\Workbench;
    class Command {
        private $_app;
        private $_rx;
        private $_call;
        public function __construct($app, $rx, $call) {
            $this->_app = $app;
            $this->_rx = "/$rx/";
            $this->_call = $call;
        }
        public function isMatch(&$command) {
            return preg_match($this->_rx, $command);
        }
        public function execute(&$arguments) {
            return call_user_func($this->_call, $this->_app, $arguments);
        }
    }
    class Workbench {
        private static $_ns;
        private static $_app;
        private static $_argv;
        private static $_command;
        private static $_commands;
        private static $_configuration;

        public static function setNamespace($ns) {
            self::$_ns = $ns;
        }

        public static function setApp($app) {
            self::$_app=$app;
        }

        public static function setArgv($argv) {
            self::$_argv = $argv;
            //set command
            self::$_command='';
            for($i=1;$i<count(self::$_argv);$i++) {
                if($i>1) self::$_command.=' ';
                self::$_command.=self::$_argv[$i];
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

        public static function registerCommand($rx, $call, $name = false) {
            if($name===false) {
                self::$_commands[] = new Command(self::$_app, $rx, $call);
            } else { self::$_commands[$name] = new Command(self::$_app, $rx, $call); }
        }

        public static function getConfiguration($key) {
            if(array_key_exists($key, self::$_configuration)) {
                return self::$_configuration[$key];
            }
            return false;
        }

        public static function namespaceApplication() {
            return str_replace('\\', '.', self::$_ns);
        }

        public static function namespaceApplicationMatch() {
            return (self::$_configuration["application"]==self::namespaceApplication());
        }

        public static function currentApplicationExists() {
            return is_file(__DIR__.'/../'.self::getConfiguration('application').'/Core/App.php');
        }

        public static function currentPublicExists() {
            return is_file(__DIR__.'/../'.self::getConfiguration('public').'/index.php');
        }

        public static function includeApplication() {
            require_once(__DIR__.'/../'.self::getConfiguration('application').'/Core/App.php');
        }

        public static function isAppMatch() {
            if(array_key_exists(self::namespaceApplication(),
                                self::getConfiguration('applications'))) {
                $rsts = self::getConfiguration('applications')[self::namespaceApplication()];
                return (preg_match('/'.$rsts["url"].'/', $_SERVER['HTTP_HOST']|$_SERVER['SERVER_NAME'])&&
                        preg_match('/'.$rsts["path"].'/', trim($_SERVER['REQUEST_URI'], '/')));
            }
            return false;
        }

        public static function run() {
            foreach(self::$_commands as $command) {
                if($command->isMatch(self::$_command)) {
                    return $command->execute(self::$_argv);
                }
            }
            self::$_commands['help']->execute(self::$_argv);
        }

        public static function addApplication($name, $url, $path) {
            if(!array_key_exists($name, self::getConfiguration('applications'))) {
                self::$_configuration['applications'][$name] = [
                    "url" => $url,
                    "path" => $path
                ];
                self::saveConfiguration();
            }
            return false;
        }
    }
