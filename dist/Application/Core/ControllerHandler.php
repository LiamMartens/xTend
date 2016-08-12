<?php
    namespace Application\Core;
    use Application\Blueprints\DataExtension;
    use Application\Blueprints\StaticDataExtension;

    /**
    * The ControllerHandler handles
    * loading and executing
    * controllers
    */
    class ControllerHandler  {
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
        public static function load($controllerName, $data = [], $ns = false) {
            // explode it on @ -> 0 will be the directive + namespace + classname
            // >1 index will be methods
            $at_explode=explode('@', $controllerName);
            $controllerName=$at_explode[0];
            // remove the model name from the array
            array_splice($at_explode, 0, 1);
            // set default namespace
            if($ns===false) { $ns=App::namespace(); }
            // extract directive
            $dot_pos=strrpos($controllerName, '.');
            $directive=($dot_pos===false) ? false : substr($controllerName, 0, $dot_pos).'.';
            // now only contains the classname and namespace
            if($dot_pos!==false) { $controllerName=substr($controllerName, $dot_pos+1); }
            // extract namespace and classname
            $back_pos=strrpos($controllerName, '\\');
            // namespace now contains default namespace or specified namespace
            $ns=($back_pos===false) ? $ns : substr($controllerName, 0, $back_pos);
            // modelName now contains the classname
            if($back_pos!==false) { $controllerName=substr($controllerName, $back_pos+1); }
            // get file path and start inclusion
            $modelPath=$directive.$controllerName;
            if(self::exists($modelPath)) {
                FileManager::include(App::models()->file($modelPath.'.php'));
                //data was passed
                $className=trim($ns, '\\').'\\'.$controllerName;
                if(($data!=null)&&(count($data)>0)) {
                    if(method_exists($controllerClassName, 'set')) {
                        foreach ($data as $key => $value) {
                            call_user_func([ $controllerClassName, 'set' ], $key, $value);
                        }
                    }
                }
                // execute requested @ functions
                // multiple methods can be called using multiple @ symbols
                foreach($at_explode as $method) {
                    if(method_exists($className, $method)) {
                        call_user_func([ $className, $method ]);
                    }
                }
                return true;
            }
            return false;
        }
    }