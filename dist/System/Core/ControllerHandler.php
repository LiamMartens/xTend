<?php
	namespace xTend
	{
		class ControllerHandler
		{
			private $_app;
			public $_controllers;
			public function __construct($app) {
				//keep current app reference for initializing controllers which might need the app to access app directives and settings
				$this->_app = $app;
				$this->_controllers = [];
				$this->_app->getErrorCodeHandler()->registerErrorCode(0x0002, "controllerhandler:invalid-controller-definition", "Error while trying to pass data to an initialized controller. Data methods not implemented.");
			}
			public function exists($controllerName) {
				//controllerName excluding @ function call
				return $this->_app->getFileHandler()->exists($this->_app->getFileHandler()->systemFile("Controllers.$controllerName.php"));
			}
			public function loadController($controllerName, $namespace = "xTend", $data = [], $createInstance = true) {
				$dot_pos = strrpos($controllerName, ".");
				$first_at_pos = ($dot_pos!==false) ? strpos($controllerName, "@", $dot_pos) : strpos($controllerName, "@");
				$controllerPath = ($first_at_pos!==false) ? substr($controllerName, 0, $first_at_pos) : $controllerName;
				//ControllerClass
				$controllerClass = explode(".", $controllerName);
				$controllerClass = $controllerClass[count($controllerClass) - 1];
				$controllerClass = explode("@", $controllerClass);
				$controllerClassName = "$namespace\\".$controllerClass[0];
				//start inclusion
				if($this->exists($controllerPath)) {
					ClassManager::includeClass($controllerClassName, $this->_app->getFileHandler()->systemFile("Controllers.".$controllerPath.".php"));
					if($createInstance) {
						//create an instance in the controllers
						//if not you'll have to instantiate it yourself
						//the function @ call will be ignored if an instance is not being created
						//app reference is passed
						$this->_controllers[$controllerPath] = new $controllerClassName($this->_app);
						//data was passed
						if(($data!=null)&&(count($data)>0)) {
							if(method_exists($this->_controllers[$controllerPath], "setData")) {
								foreach ($data as $key => $value) {
									$this->_controllers[$controllerPath]->setData($key,$value);
								}
							} else { throw $this->_app->getErrorCodeHandler()->getError(0x0002)->getException(); }
						}
						//execute requested @ functions
						//Multiple methods can be called using multiple @ symboles
						//class@funcA@funcB
						$totalclassparts = count($controllerClass);
						for($i=1;$i<$totalclassparts;$i++) {
							if(method_exists($this->_controllers[$controllerPath], $controllerClass[$i])) {
								$this->_controllers[$controllerPath]->{$controllerClass[$i]}();
							}
						}
						return $this->_controllers[$controllerPath];
					}
					return true;
				}
				return false;
			}
			public function getController($controllerName=false) {
				//the controller name here also does not include any @ functions
				if(($controllerName==false)&&(count($this->_controllers)==1))
					return $this->_controllers[array_keys($this->_controllers)[0]];
				elseif($controllerName==false) return false;
				if(array_key_exists($controllerName, $this->_controllers))
					return $this->_controllers[$controllerName];
				return false;
			}
		}
	}