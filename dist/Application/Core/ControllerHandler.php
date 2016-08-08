<?php
    namespace Application\Core;
    use Application\Blueprints\DataExtension;
    use Application\Blueprints\StaticDataExtension;

    /**
    * The ControllerHandler handles
    * loading and executing
    * controllers
    */
    class ControllerHandler
    {
        /** @var array Containing controller register names and their class names */
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
        public function load($controllerName, $data = [], $ns = false) {
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
                self::$_controllers_names[$controllerPath] = $controllerClassName;
                //data was passed
                if(($data!=null)&&(count($data)>0)) {
                    if(method_exists($controllerClassName, 'set')) {
                        foreach ($data as $key => $value) {
                            call_user_func([ $controllerClassName, 'set' ], $key, $value);
                        }
                    }
                }
                //execute requested @ functions
                //Multiple methods can be called using multiple @ symboles
                //class@funcA@funcB
                $totalclassparts = count($split);
                $i=1; while($i<$totalclassparts) {
                    if(method_exists([ $controllerClassName, $split[$i] ])) {
                        $return_data = call_user_func([ $controllerClassName, $split[$i] ], ModelHandler::names());
                        if(is_array($return_data)) { echo json_encode($return_data); }
                    } ++$i;
                }
                return true;
            }
            return false;
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
