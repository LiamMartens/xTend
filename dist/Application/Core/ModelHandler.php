<?php
    namespace xTend\Core;
    /**
    * The ModelHandler handles loading
    * models
    */
    class ModelHandler
    {
        /** @var xTend\Core\App Current application */
        private $_app;
        /** @var array Contains all loaded models */
        private $_models;
        /** @var array Contains model names */
        private $_models_names;

        /**
        * @param xTend\Core\App $app
        */
        public function __construct($app) {
            //keep current app reference for initializing models which might need the app to access app directives and settings
            $this->_app = $app;
            $this->_models = [];
            $this->_models_names = [];
        }

        /**
        * Checks whether a model exists
        *
        * @param string $modelName
        *
        * @return boolean
        */
        public function exists($modelName) {
            return $this->_app->getModelsDirectory()->file("$modelName.php")->exists();
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
        public function loadModel($modelName, $ns = false, $createInstance = true) {
            //if create instance is set to false their will not be an instance of the model available
            //any reference to this model inclusion is also lost except for the fact that the file has been required
            //keep in mind also that the modelName also includes any directives you need to enter
            //so modelName Foo.Bar will be located at /System/Models/Foo/Bar.php
            //The namespace will differ but you can set this using the parameter -> by default xTend namespace
            //set namespace
            if($ns===false) $ns=$this->_app->getNamespace();
            //extract directive
            $dot_pos = strrpos($modelName, ".");
            $directive = ($dot_pos!==false) ? substr($modelName, 0, $dot_pos) : false;
            if($directive!==false) { $directive.="."; }
            if($dot_pos!==false) { $modelName=substr($modelName, $dot_pos+1); }
            //extract namespace
            $back_pos = strrpos($modelName, "\\");
            $namespace = ($back_pos!==false) ? substr($modelName, 0, $back_pos) : false;
            if($back_pos!==false) { $modelName=substr($modelName, $back_pos+1); }
            //extract function calls and real controller name
            $modelPath = "$directive$modelName";
            $className = (($namespace!==false) ? $namespace : $ns)."\\".$modelName;
            if($this->exists($modelPath)) {
                ClassManager::includeClass($className, $this->_app->getModelsDirectory()->file("$modelPath.php"));
                if($createInstance) {
                    //by default a reference to the app is passed as well in order to make it's directives and settings available
                    try {
                        $this->_models[$className] = new $className($this->_app);
                        $this->_models_names[$modelName] = &$this->_models[$className];
                        return $this->_models[$className];
                    } catch(\Exception $ex) { return true; }
                }
                return true;
            }
            return false;
        }

        /**
        * Gets a model by name or gets the first model
        *
        * @param boolean|string $modelName
        *
        * @param model|boolean
        */
        public function getModel($modelName=false) {
            if(($modelName==false)&&(count($this->_models)>0))
                return $this->_models[array_keys($this->_models)[0]];
            elseif($modelName==false) return false;
            if(array_key_exists($modelName, $this->_models))
                return $this->_models[$modelName];
            elseif(array_key_exists($this->_app->getNamespace()."\\$modelName", $this->_models))
                return $this->_models[$this->_app->getNamespace()."\\$modelName"];
            return false;
        }

        /**
        * Gets all model instances
        *
        * @return array
        */
        public function getModels() {
            return $this->_models;
        }

        /**
        * Gets all model instances by registered name
        *
        * @return array
        */
        public function getModelsNames() {
            return $this->_models_names;
        }
    }