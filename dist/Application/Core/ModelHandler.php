<?php
    namespace Application\Core;

    /**
    * The ModelHandler handles loading
    * models
    */
    class ModelHandler {
        /** @var array Contains the register names */
        private static $_names = [];
        /** @var array Contains the register names and their classname */
        private static $_name_bindings = [];
        
        /**
        * Checks whether a model exists
        *
        * @param string $modelName
        *
        * @return boolean
        */
        public static function exists($modelName) {
            return App::models()->file($modelName.'.php')->exists();
        }

        /**
        * Loads a model
        *
        * @param string $modelName
        * @param string|boolean $ns
        * @param boolean $createInstance
        *
        * @return model|boolean
        */
        public static function load($modelName, $ns = false) {
            // explode it on @ -> 0 will be the directive + namespace + classname
            // >1 index will be methods
            $at_explode=explode('@', $modelName);
            $modelName=$at_explode[0]; 
            // remove the model name from the array
            array_splice($at_explode, 0, 1);
            // set default namespace
            if($ns===false) { $ns=App::namespace(); }
            // extract directive
            $dot_pos=strrpos($modelName, '.');
            $directive=($dot_pos===false) ? false : substr($modelName, 0, $dot_pos).'.';
            // now only contains the classname and namespace
            if($dot_pos!==false) { $modelName=substr($modelName, $dot_pos+1); }
            // register name should be the classname and namespace of the original load
            $registerName=$modelName;
            // extract namespace and classname
            $back_pos=strrpos($modelName, '\\');
            // namespace now contains default namespace or specified namespace
            $ns=($back_pos===false) ? $ns : substr($modelName, 0, $back_pos);
            // modelName now contains the classname
            if($back_pos!==false) { $modelName=substr($modelName, $back_pos+1); }
            // get file path and start inclusion
            $modelPath=$directive.$modelName;
            if(self::exists($modelPath)) {
                $className=trim($ns, '\\').'\\'.$modelName;
                FileManager::include(App::models()->file($modelPath.'.php'));
                self::$_names[] = $registerName;
                self::$_name_bindings[$registerName] = $className;
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

        /**
        * Finds a model class name
        *
        * @param string $name
        *
        * @return string(className)
        */
        public static function find($name=null) {
            if(($name===null)&&(count(self::$_names)>0)) {
                return self::$_name_bindings[self::$_names[0]];
            } elseif(isset(self::$_name_bindings[$name])) {
                return self::$_name_bindings[$name];
            }
            return false;
        }
    }
