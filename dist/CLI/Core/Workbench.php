<?php
    namespace xTend\Workbench;
    use xTend\Workbench\Workbench;
    
    class Workbench {
        /** @var string Contains the directory from where workbench is run */
        public static $directory=__DIR__.'/../..';
        /** @var array Contains workbench configuration */
        public static $configuration=[];
        /** @var array Contains the commands */
        public static $commands=[];
        /** @var array Contains the arguments (excluding own filename) */
        public static $argv=[];
        /** @var string Contains the executed command (excluding arguments) */
        public static $command;

        /**
        *
        * PRIVATE HELPER METHODS
        *
        **/
        private static function json($fileName) {
            return json_decode(file_get_contents($fileName), true);
        }

        /**
        *
        * CORE PUBLIC METHODS
        *
        **/
        
        /**
        * Loads Workbench configuration
        */
        public static function configure() {
            self::$configuration = self::json(self::$directory.'/CLI/Config/configuration.json');
        }

        /**
        * Saves the configuration
        */
        public static function save() {
            file_put_contents(self::$directory.'/CLI/Config/configuration.json', json_encode(self::$configuration));
        }

        /**
        * Loads the commands files and the Command object
        */
        public static function commands() {
            require(self::$directory.'/CLI/Core/Command.php');
            $commands=array_diff(scandir(self::$directory.'/CLI/Commands'), ['.', '..']);
            foreach($commands as $command) {
                require(self::$directory.'/CLI/Commands/'.$command);
            }
        }

        /**
        * Includes the current application's core file
        */
        public static function application() {
            require(self::$directory.'/'.self::get('application').'/Core/App.php');
        }

        /**
        * Runs the workbench
        *
        * @param $argv array Array of command line arguments
        */
        public static function run($argv) {
            // remove first argument as it is just workbench
            self::$argv=array_slice($argv, 1);
            // build full command
            self::$command = implode(' ', self::$argv);
            // check the commands
            foreach(self::$commands as $command) {
                if($command->match(self::$command)) {
                    return $command->execute(self::$argv);
                }
            } self::$commands['help']->execute(self::$argv);
        }

        public static function match($restrictions) {
            $path=trim($restrictions['path'], '/'); $request=trim($_SERVER['REQUEST_URI'], '/');
            $domain_match=(($_SERVER['HTTP_HOST']|$_SERVER['SERVER_NAME']==trim($restrictions['url']))||($restrictions['url']=='*'));
            $path_match=(($path=='*')||($request==$path)||(substr($request, 0, strlen($path))==$path));
            return $domain_match&&$path_match;
        }

        /**
        *
        * PUBLIC HELPER METHODS
        *
        **/

        /**
        * Returns a configuration variable
        *
        * @param string $key
        *
        * @return mixed
        */
        public static function get($key) {
            if(isset(self::$configuration[$key])) {
                return self::$configuration[$key];
            }
            return false;
        }

        /**
        * Sets a configuration variable
        *
        * @param string $key
        * @param mixed $value
        * 
        * @return mixed
        */
        public static function set($key, $value) {
            self::$configuration[$key]=$value;
            return $value;
        }

        /**
        * Adds a new application
        *
        * @param string $name
        * @param string $domain
        * @param string $path
        *
        * @return array
        */
        public static function new($name, $domain, $path) {
            self::$configuration['applications'][$name] = [
                "url" => $domain,
                "path" => $path
            ];
            self::save();
            return self::get('applications')[$name];
        }

        /**
        * Removes an application from the config
        *
        * @param string $name
        *
        * @return boolean
        */
        public static function remove($name) {
            if(isset(self::get('applications')[$name])) {
                unset(self::$configuration['applications'][$name]);
                self::save();
                return true;
            }
            return false;
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
                self::$commands[] = new Command($rx, $call);
            } else { self::$commands[$name] = new Command($rx, $call); }
        }

        /**
        * Converts application name into a namespace
        *
        * @param string $name
        *
        * @return string
        */
        public static function namespace($name) {
            return str_replace('.', '\\\\', $name);
        }

        /**
        * Replaces a certain namespace in a file
        *
        * @param string $fileName
        * @param string $old
        * @param sring $new
        */
        public static function filespace($fileName, $old, $new) {
            $file=fopen($fileName, 'r+'); $new_content='';
            while(!feof($file)) {
                $line=fgets($file);
                $whs=[]; preg_match('/^(\s*)/', $line, $whs);
                $line=trim($line);
                $line=preg_replace('/^(namespace|use)\s+'.$old.'((?:(?:\\\\.+?)|\s*)*);/', '$1 '.$new.'$2;', $line);
                $line=preg_replace('/^(namespace|use)\s+'.$old.'((?:(?:\\\\.+?)|\s*)*)\s*{/', '$1 '.$new.'$2 {', $line);
                $new_content.=$whs[0].$line.PHP_EOL;
            }
            // reset file pointer to beginning to truncate file
            rewind($file); ftruncate($file, 0);
            fwrite($file, $new_content);
            return fclose($file);
        }
    }