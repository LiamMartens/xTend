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
    class Helpers {
        public static function generate() {
            return base64_encode(random_bytes(8));
        }
        public static function json($data) {
            if(is_string($data)) {
                return json_decode($data, true);
            }
            return json_encode($data);
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

        public static function removeApplication($name) {
            if(array_key_exists($name, self::getConfiguration('applications'))) {
                unset(self::$_configuration['applications'][$name]);
                self::saveConfiguration();
            }
            return false;
        }

        public static function setApplication($name) {
            self::$_configuration['application'] = $name;
            self::saveConfiguration();
        }

        public static function setPublic($public) {
            self::$_configuration['public'] = $public;
            self::saveConfiguration();
        }
    }