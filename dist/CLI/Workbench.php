<?php
    namespace xTend;
    use xTend\Workbench\Command;

    /**
    * The Workbench handles registering
    * and executing commands
    */
    class Workbench {
        /** @var string Contains the application namespace */
        private static $_ns;
        /** @var array Contains the command line arguments */
        private static $_argv;
        /** @var xTend\Workbench\Command Contains the current executed command */
        private static $_command;
        /** @var array Contains all registered commands */
        private static $_commands;
        /** @var array Contains the Workbench configuration */
        private static $_configuration;

        /**
        * Sets the workbench namespace
        *
        * @param string $ns
        */
        public static function namespace($ns) {
            self::$_ns = $ns;
        }

        /**
        * Sets the workbench arguments
        *
        * @param array $argv
        */
        public static function argv($argv) {
            self::$_argv = $argv;
            //set command
            self::$_command='';
            $args_count=count(self::$_argv);
            $i=1; while($i<$args_count) {
                if($i>1) self::$_command.=' ';
                self::$_command.=self::$_argv[$i]; ++$i;
            }
        }

        public static function configuration() {
            self::$_configuration = json_decode(file_get_contents(__DIR__.'/../.workbench'), true);
        }

        public static function save() {
            file_put_contents(__DIR__.'/../.workbench', json_encode(self::$_configuration));
        }

        public static function commands() {
            require_once(__DIR__."/../.commands");
        }

        /**
        * Registers a command
        *
        * @param string $rx
        * @param callable $call
        * @param string $name
        */
        public static function register($rx, $call, $name = false) {
            if($name===false) {
                self::$_commands[] = new Command($rx, $call);
            } else { self::$_commands[$name] = new Command($rx, $call); }
        }

        /**
        * @param mixed $key
        *
        * @return mixed
        */
        public static function get($key) {
            if(isset(self::$_configuration[$key])) {
                return self::$_configuration[$key];
            }
            return false;
        }

        /**
        * Checks whether an app match
        *
        * @return boolean
        */
        public static function match() {
            if(isset(self::$_configuration['applications'][self::$_ns])) {
                $rsts = self::$_configuration['applications'][self::$_ns];
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

        public static function start() {
            // Include command class
            require(__DIR__.'/Command.php');
            // Check for Workbench integrity
            if(self::$_configuration["application"]!=self::$_ns) {
                die("Current application doesn't match the workbench namespace\n");
            }
            if(!is_file(__DIR__.'/../'.self::$_configuration['application'].'/Core/App.php')) {
                die("Application '".self::$_configuration['application']."' not found\n");
            }
            if(!is_file(__DIR__.'/../'.self::$_configuration['public'].'/index.php')) {
                die("index.php not found in public directory '".self::$_configuration['public']."'\n");
            }
            // Include the App file
            require_once(__DIR__.'/../'.self::$_configuration['application'].'/Core/App.php');
        }

        /**
        * Runs the workbench
        *
        * @return mixed
        */
        public static function run() {
            foreach(self::$_commands as $command) {
                if($command->match(self::$_command)) {
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
        public static function add($name, $url, $path) {
            if(!isset(self::$_configuration['applications'][$name])) {
                self::$_configuration['applications'][$name] = [
                    "url" => $url,
                    "path" => $path
                ];
                self::save();
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
        public static function remove($name) {
            if(isset(self::$_configuration['applications'][$name])) {
                unset(self::$_configuration['applications'][$name]);
                self::save();
                return true;
            }
            return false;
        }

        /**
        * Sets the application
        *
        * @param string $name
        */
        public static function application($name) {
            // replace current namespace line in .commands and workbench
            $contents=file_get_contents(__DIR__.'/../.commands');
            $contents=preg_replace('/namespace '.str_replace('.', '\\\\', self::$_configuration['application']).';/',
                                        'namespace '.$name.';',
                                        $contents);
            file_put_contents(__DIR__.'/../.commands',$contents);
            $contents=file_get_contents(__DIR__.'/../workbench');
            $contents=preg_replace('/namespace '.str_replace('.', '\\\\', self::$_configuration['application']).';/',
                                        'namespace '.$name.';',
                                        $contents);
            file_put_contents(__DIR__.'/../workbench',$contents);
            self::$_configuration['application'] = $name;
            self::save();
        }

        /**
        * Sets the public directory
        *
        * @param string $name
        */
        public static function public($public) {
            rename(self::$_configuration['public'], $public);
            self::$_configuration['public'] = $public;
            self::save();
        }
    }
