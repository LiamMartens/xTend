<?php
    namespace xTend\Core;
    class ControllerHandler
    {
        private $_app;
        public $_controllers;
        public function __construct($app) {
            $this->_app = $app;
            $this->_controllers = [];
        }

        /*
        * Checks whether a controller exists
        *
        * @param string $controllerName
        *
        * @return boolean
        */
        public function exists($controllerName) {
            //controllerName excluding @ function call
            return $this->_app->getControllersDirectory()->file("$controllerName.php")->exists();
        }

        /*
        * Loads a controller
        *
        * @param string $controllerName
        * @param array $data
        * @param string|boolean $ns
        * @param boolean $createInstance
        *
        * @return controller|boolean
        */
        public function loadController($controllerName, $data = [], $ns = false, $createInstance = true) {
            //
            //  controller => "My.Directive.My\Namespace\ControllerName@function@function
            //
            //set default namespace
            if($ns===false) $ns=$this->_app->getNamespace();
            //extract directive
            $dot_pos = strrpos($controllerName, ".");
            $directive = ($dot_pos!==false) ? substr($controllerName, 0, $dot_pos) : false;
            if($directive!==false) { $directive.="."; }
            if($dot_pos!==false) { $controllerName=substr($controllerName, $dot_pos+1); }
            //extract namespace
            $back_pos = strrpos($controllerName, "\\");
            $namespace = ($back_pos!==false) ? substr($controllerName, 0, $back_pos) : false;
            if($back_pos!==false) { $controllerName=substr($controllerName, $back_pos+1); }
            //extract function calls and real controller name
            $split = explode("@", $controllerName);
            $controllerClassName = (($namespace!==false) ? $namespace : $ns)."\\".$split[0];
            //start inclusion
            $controllerPath = $directive.$split[0];
            //start inclusion
            if($this->exists($controllerPath)) {
                ClassManager::includeClass($controllerClassName, $this->_app->getControllersDirectory()->file("$controllerPath.php"));
                if($createInstance) {
                    //create an instance in the controllers
                    //if not you'll have to instantiate it yourself
                    //the function @ call will be ignored if an instance is not being created
                    //app reference is passed
                    $this->_controllers[$controllerClassName] = new $controllerClassName($this->_app);
                    //data was passed
                    if(($data!=null)&&(count($data)>0)) {
                        if(method_exists($this->_controllers[$controllerClassName], "setData")) {
                            foreach ($data as $key => $value) {
                                $this->_controllers[$controllerClassName]->setData($key,$value);
                            }
                        } else { throw $this->_app->getStatusCodeHandler()->getStatus(0x0002)->getException(); }
                    }
                    //execute requested @ functions
                    //Multiple methods can be called using multiple @ symboles
                    //class@funcA@funcB
                    $totalclassparts = count($split);
                    for($i=1;$i<$totalclassparts;$i++) {
                        if(method_exists($this->_controllers[$controllerClassName], $split[$i])) {
                            $return_data = $this->_controllers[$controllerClassName]->{$split[$i]}();
                            if(is_array($return_data)) { echo json_encode($return_data); }
                        }
                    }
                    return $this->_controllers[$controllerClassName];
                }
                return true;
            }
            return false;
        }

        /*
        * Returns a controller by name or the first one
        *
        * @param string|boolean $controllerName
        *
        * @return controller|boolean
        */
        public function getController($controllerName=false) {
            //the controller name here also does not include any @ functions
            if(($controllerName==false)&&(count($this->_controllers)>0))
                return $this->_controllers[array_keys($this->_controllers)[0]];
            elseif($controllerName==false) return false;
            if(array_key_exists($controllerName, $this->_controllers))
                return $this->_controllers[$controllerName];
            elseif(array_key_exists($this->_app->getNamespace()."\\$controllerName", $this->_controllers))
                return $this->_controllers[$this->_app->getNamespace()."\\$controllerName"];
            return false;
        }
    }
