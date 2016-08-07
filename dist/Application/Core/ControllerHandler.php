<?php
    namespace Application\Core;

    /**
    * The ControllerHandler handles
    * loading and executing
    * controllers
    */
    class ControllerHandler
    {
        /** @var array Set of controllers */
        private static $_controllers=[];
        /** @var array Containing controller names */
        private static $_controllers_names=[];

        /**
        * Checks whether a controller exists
        *
        * @param string $controllerName
        *
        * @return boolean
        */
        public static function exists($controllerName) {
            //controllerName excluding @ function call
            return App::controllers()->file($controllerName.'.php')->exists();
        }

        /**
        * Loads a controller
        *
        * @param string $controllerName
        * @param array $data
        * @param string|boolean $ns
        * @param boolean $createInstance
        *
        * @return controller|boolean
        */
        public function load($controllerName, $data = [], $ns = false, $createInstance = false) {
            //
            //  controller => "My.Directive.My\Namespace\ControllerName@function@function
            //
            //set default namespace
            $at_index=strpos($controllerName, '@');
            $registerName=($at_index===false) ? $controllerName : substr($controllerName, 0, $at_index);
            if($ns===false) $ns=App::namespace();
            //extract directive
            $dot_pos = strrpos($controllerName, '.');
            $directive = ($dot_pos!==false) ? substr($controllerName, 0, $dot_pos) : false;
            if($directive!==false) { $directive.='.'; }
            if($dot_pos!==false) { $controllerName=substr($controllerName, $dot_pos+1); }
            //extract namespace
            $back_pos = strrpos($controllerName, '\\');
            $namespace = ($back_pos!==false) ? substr($controllerName, 0, $back_pos) : false;
            if($back_pos!==false) { $controllerName=substr($controllerName, $back_pos+1); }
            //extract function calls and real controller name
            $split = explode('@', $controllerName);
            $controllerClassName = (($namespace!==false) ? $namespace : $ns)."\\".$split[0];
            //start inclusion
            $controllerPath = $directive.$split[0];
            //start inclusion
            if(self::exists($controllerPath)) {
                FileManager::include(App::controllers()->file($controllerPath.'.php'));
                if($createInstance) {
                    //create an instance in the controllers
                    //if not you'll have to instantiate it yourself
                    //the function @ call will be ignored if an instance is not being created
                    //app reference is passed
                    self::$_controllers[$controllerClassName] = new $controllerClassName(ModelHandler::names());
                    self::$_controllers_names[$controllerPath] = &self::$_controllers[$controllerClassName];
                    //data was passed
                    if(($data!=null)&&(count($data)>0)) {
                        if(method_exists(self::$_controllers[$controllerClassName], 'setData')) {
                            foreach ($data as $key => $value) {
                                self::$_controllers[$controllerClassName]->setData($key,$value);
                            }
                        }
                    }
                    //execute requested @ functions
                    //Multiple methods can be called using multiple @ symboles
                    //class@funcA@funcB
                    $totalclassparts = count($split);
                    $i=1; while($i<$totalclassparts) {
                        if(method_exists(self::$_controllers[$controllerClassName], $split[$i])) {
                            $return_data = self::$_controllers[$controllerClassName]->{$split[$i]}(ModelHandler::names());
                            if(is_array($return_data)) { echo json_encode($return_data); }
                        } ++$i;
                    }
                    return self::$_controllers[$controllerClassName];
                }
                return true;
            }
            return false;
        }

        /**
        * Returns a controller by name or the first one
        *
        * @param string|boolean $controllerName
        *
        * @return controller|boolean
        */
        public function get($controllerName=false) {
            //the controller name here also does not include any @ functions
            if(($controllerName==false)&&(count(self::$_controllers)>0))
                return self::$_controllers[array_keys(self::$_controllers)[0]];
            elseif($controllerName==false) return false;
            if(isset(self::$_controllers[$controllerName]))
                return self::$_controllers[$controllerName];
            elseif(isset(self::$_controllers[App::namespace().'\\'.$controllerName]))
                return self::$_controllers[App::namespace().'\\'.$controllerName];
            return false;
        }

        /**
        * Gets all controller instances
        *
        * @return array
        */
        public static function all() {
            return self::$_controllers;
        }

        /**
        * Gets all controller instances by registered name
        *
        * @return array
        */
        public static function names() {
            return self::$_controllers_names;
        }
    }
