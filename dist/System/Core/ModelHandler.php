<?php
	namespace xTend
	{
		class ModelHandler
		{
			private $_app;
			public $_models;
			public function __construct($app) {
				//keep current app reference for initializing models which might need the app to access app directives and settings
				$this->_app = $app;
				$this->_models = [];
			}
			public function exists($modelName) {
				return $this->_app->getFileHandler()->exists($this->_app->getFileHandler()->systemFile($this->_app->getModelsDirectory().".$modelName.php"));
			}
			public function loadModel($modelName, $ns = "xTend", $createInstance = true) {
				//if create instance is set to false their will not be an instance of the model available
				//any reference to this model inclusion is also lost except for the fact that the file has been required
				//keep in mind also that the modelName also includes any directives you need to enter
				//so modelName Foo.Bar will be located at /System/Models/Foo/Bar.php
				//The namespace will differ but you can set this using the parameter -> by default xTend namespace
                //
                //  controller => "My.Directive.My\Namespace\ControllerName@function@function
                //
                //extract directive
                $dot_pos = strrpos($modelName, ".");
                $directive = ($dot_pos!==false) ? substr($modelName, 0, $dot_pos) : false;
                if($dot_pos!==false) { $modelName=substr($modelName, $dot_pos+1); }
                //extract namespace
                $back_pos = strrpos($modelName, "\\");
                $namespace = ($back_pos!==false) ? substr($modelName, 0, $back_pos) : false;
                if($back_pos!==false) { $modelName=substr($modelName, $back_pos+1); }
                //extract function calls and real controller name
                $modelPath = "$directive.$modelName";
                $className = (($namespace!==false) ? $namespace : $ns)."\\".$modelName;
				if($this->exists($modelPath)) {
					ClassManager::includeClass($className, $this->_app->getFileHandler()->systemFile($this->_app->getModelsDirectory().".$modelPath.php"));
					if($createInstance) {
						//by default a reference to the app is passed as well in order to make it's directives and settings available
						$this->_models[$className] = new $className($this->_app);
						return $this->_models[$className];
					}
					return true;
				}
				return false;
			}
			public function getModel($modelName=false) {
				if(($modelName==false)&&(count($this->_models)==1))
					return $this->_models[array_keys($this->_models)[0]];
				elseif($modelName==false) return false;
				if(array_key_exists($modelName, $this->_models))
					return $this->_models[$modelName];
				return false;
			}
		}
	}