<?php
    namespace xTend\Objects;
    /**
    * The Route objects handles
    * routes
    */
    class Route
    {
        /** @var xTend\Core\App Current application */
        private $_app;
        /** @var string Contains the handle of the route */
        private $_handle;
        /** @var mixed Contains the route */
        private $_route;
        /** @var string|boolean Cotnains the alias of the route */
        private $_alias;

        /**
        * @return string
        */
        public function getHandle() { return $this->_handle; }

        /**
        * @return mixed
        */
        public function getRoute() { return $this->_route; }

        /**
        * @return string|boolean
        */
        public function getAlias() { return $this->_alias; }

        /**
        * @param xTend\Core\App $app
        * @param string $handle
        * @param mixed $route
        * @param string|boolean $alias
        */
        public function __construct($app, $handle, $route, $alias=false) {
            $this->_app = $app;
            $this->_handle = trim($handle, "/");
            $this->_route = $route;
            $this->_alias = $alias;
        }

        /**
        * @param array $data
        * @param boolean $inc_url
        */
        public function navigate($data = [], $inc_url = true) {
            if(is_string($this->_handle)) {
                return $this->_app->getUrlHandler()->navigate($this->_handle);
            }
            return false;
        }

        /**
        * @param array $parameters
        * @param array $data
        *
        * @return boolean
        */
        public function to($parameters = [], $data = []) {
            return $this->_app->getUrlHandler()->to($this->_handle, $parameters, $data);
        }

        /**
        * Executes the route
        *
        * @return boolean
        */
        public function execute() {
            //this function will execute whatever is attached to the route
            if(is_callable($this->_route)) {
                //the route is a function -> so call it
                echo call_user_func($this->_route, $this->_app, $this->_app->getRequestHandler()->getRequest());
            } elseif(is_string($this->_route)) {
                //the route is a string, so just echo it
                echo $this->_route;
            } elseif(is_array($this->_route)) {
                //data array passed
                //view, controller, model, data
                $data=(isset($this->_route['data'])&&(is_array($this->_route["data"]))) ? $this->_route["data"] : [];
                //check and load one model
                if(isset($this->_route['model'])) {
                    $this->_app->getModelHandler()->loadModel($this->_route["model"]);
                }
                //check and load multiple models
                if(isset($this->_route['models'])) {
                    foreach ($this->_route["models"] as $model) {
                        $this->_app->getModelHandler()->loadModel($model);
                    }
                }
                //check for controller
                $controller_found=false;
                if(isset($this->_route['controller'])) {
                    $this->_app->getControllerHandler()->loadController($this->_route["controller"], $data);
                    $controller_found=true;
                }
                //check for multiple controllers
                if(isset($this->_route['controllers'])) {
                    foreach ($this->_route["controllers"] as $controller) {
                        $this->_app->getControllerHandler()->loadController($controller, $data);
                    }
                    $controller_found=true;
                }
                //check for view
                //For views, don't pass data, when a controller already has the data
                //No need for duplicate data
                if(isset($this->_route['view'])) {
                    $version = false; if(isset($this->_route['version'])) { $version = $this->_route["version"]; }
                    $this->_app->getViewHandler()->loadView($this->_route["view"], ($controller_found) ? [] : $data, $version);
                }
                //check for multiple views
                if(isset($this->_route['views'])) {
                    foreach ($this->_route["views"] as $view) {
                        $this->_app->getViewHandler()->loadView($view, ($controller_found) ? [] : $data);
                    }
                }
            } else return false;
            return true;
        }

        /**
        * Checks whether the request uri is a match
        *
        * @param string $request
        *
        * @return boolean
        */
        public function isMatch($request) {
            if(is_array($this->_route)&&isset($this->_route['environment'])&&($this->_route['environment']!=$this->_app->getEnvironment())) {
                return false;
            }
            if(is_string($this->_handle)) {
                //split handle for multi handle
                $handles = explode("|", $this->_handle);
                foreach($handles as $handle) {
                    $handle_matched=true;
                    //clear previous data
                    $this->_app->getUrlHandler()->clearData();
                    //ignore starting and trailing slashes
                    $ex_request = explode("/", trim($request, "/"));
                    $ex_handle = explode("/", $handle);
                    //if the amount of parts dont comply just, end
                    if(count($ex_request)!=count($ex_handle)) continue;
                    //check all parts of the handle and see whether they match up  to the request
                    $ex_count=count($ex_handle); $rx_matches;
                    $i=0; while($i<$ex_count) {
                        //check
                        $handle_part=$ex_handle[$i];
                        $request_part=$ex_request[$i];
                        //check {get} parameter first
                        if($i==$ex_count-1) {
                            //checking the last part of the handle
                            //+{get}
                            if(preg_match("/^(.*)(\+{get})$/i", $handle_part)) {
                                //$handle_part ends with +{get}
                                //thus get parameters are allowed
                                //get rid of +{get} in the handle
                                $handle_part=substr($handle_part, 0, strlen($handle_part)-6);
                                //get rid of anything after first question mark in the request part
                                $qm_pos = strpos($request_part, "?");
                                if($qm_pos!==false) {
                                    //GET part is allowed and existing
                                    if(count($_GET)===0)
                                        parse_str(substr($request_part, $qm_pos+1), $_GET);
                                    //remove GET part from URL
                                    $request_part=substr($request_part, 0, $qm_pos);
                                }
                            }
                        }
                        //check up
                        //most complicated structure -> regexed URL variable
                        if(preg_match("/^(rx)(\{)([a-zA-Z0-9_]+)(\})(\{)(.*)(\})$/", $handle_part, $rx_matches)&&
                            preg_match("/^".$rx_matches[6]."$/", $request_part)) {
                            //regex for URL variable matches and handle is a regexed variable
                            //setData on the UrlHandler to set URL parameter with name and value
                            $this->_app->getUrlHandler()->setData($rx_matches[3], $request_part);
                        } elseif(preg_match("/^(\{)([a-zA-Z0-9_]+)(\})$/", $handle_part, $rx_matches)) {
                            //the handle is a non regex URL variable
                            //just set whatever is in the URL to the variable
                            $this->_app->getUrlHandler()->setData($rx_matches[2], $request_part);
                        } elseif(
                            !((preg_match("/^(rx)(\{)(.*)(\})$/", $handle_part, $rx_matches)&& //its a regexed part and it matches
                            preg_match("/".$rx_matches[3]."/", $request_part)) ||
                            preg_match("/^(\*+)$/", $handle_part) || //its just a bunch of wildcards
                            ($request_part==$handle_part)) /**they just equal each other*/) {
                            //if all of te above fails, return false
                            $handle_matched=false;
                        } ++$i;
                    }
                    if(!$handle_matched) continue;
                    //set the route on the UrlHandler
                    $this->_app->getUrlHandler()->setRoute($this);
                    return true;
                }
            }
            return false;
        }

        public function __toString() {
            if(is_string($this->_route)) {
                return $this->_route;
            } elseif(is_array($this->_route)) {
                return json_encode($this->_route);
            } elseif(is_callable($this->_route)) {
                return "function";
            }
            return "NULL";
        }
    }
