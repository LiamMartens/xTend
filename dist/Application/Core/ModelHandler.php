<?php
    namespace Application\Core;

    /**
    * The ModelHandler handles loading
    * models
    */
    class ModelHandler {
        /** @var array Contains all loaded models */
        private static $_models=[];
        /** @var array Contains model names */
        private static $_models_names=[];

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
        public static function load($modelName, $ns = false, $createInstance = false) {
            //if create instance is set to false their will not be an instance of the model available
            //any reference to this model inclusion is also lost except for the fact that the file has been required
            //keep in mind also that the modelName also includes any directives you need to enter
            //so modelName Foo.Bar will be located at /System/Models/Foo/Bar.php
            //The namespace will differ but you can set this using the parameter -> by default xTend namespace
            //set namespace
            if($ns===false) {$ns=App::namespace();}
            //extract directive
            $dot_pos = strrpos($modelName, '.');
            $directive = ($dot_pos!==false) ? substr($modelName, 0, $dot_pos) : false;
            if($directive!==false) { $directive.='.'; }
            if($dot_pos!==false) { $modelName=substr($modelName, $dot_pos+1); }
            //extract namespace
            $back_pos = strrpos($modelName, '\\');
            $namespace = ($back_pos!==false) ? substr($modelName, 0, $back_pos) : false;
            if($back_pos!==false) { $modelName=substr($modelName, $back_pos+1); }
            //extract function calls and real controller name
            $modelPath = $directive.$modelName;
            $className = (($namespace!==false) ? $namespace : $ns).'\\'.$modelName;
            if(self::exists($modelPath)) {
                FileManager::include(App::models()->file($modelPath.'.php'));
                if($createInstance) {
                    self::$_models[$className] = new $className();
                    self::$_models_names[$modelName] = &self::$_models[$className];
                    return self::$_models[$className];
                }
                return true;
            }
            return false;
        }

        /**
        * Gets a model by name or gets the first model
        * (only those of whom an instance is available)
        *
        * @param boolean|string $modelName
        *
        * @param model|boolean
        */
        public static function get($modelName=false) {
            if(($modelName==false)&&(count(self::$_models)>0))
                return self::$_models[array_keys(self::$_models)[0]];
            elseif($modelName==false) return false;
            if(isset(self::$_models[$modelName]))
                return self::$_models[$modelName];
            elseif(isset(self::$_models[App::namespace().'\\'.$modelName]))
                return self::$_models[App::namespace().'\\'.$modelName];
            return false;
        }

        /**
        * Gets all model instances
        *
        * @return array
        */
        public static function all() {
            return self::$_models;
        }

        /**
        * Gets all model instances by registered name
        *
        * @return array
        */
        public static function names() {
            return self::$_models_names;
        }
    }
