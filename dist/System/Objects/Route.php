<?php
	namespace xTend;
	class Route
	{
		private $_app;
		private $_handle;
		private $_route;
		private $_alias;

		public function getHandle() { return $this->_handle; }
		public function getRoute() { return $this->_route; }
		public function getAlias() { return $this->_alias; }

		public function __construct($app, $handle, $route, $alias=false) {
			$this->_app = $app;
			$this->_handle = trim($handle, "/");
			$this->_route = $route;
			$this->_alias = $alias;
		}

		public function navigate() {
			if(is_string($this->_handle)) {
				$this->_app->getUrlHandler()->navigate($this->_handle);
			}
		}

		public function execute() {
			//this function will execute whatever is attached to the route
			if(is_callable($this->_route)) {
				//the route is a function -> so call it
				echo call_user_func($this->_route, $this->_app);
			} elseif(is_string($this->_route)) {
				//the route is a string, so just echo it
				echo $this->_route;
			} elseif(is_array($this->_route)) {
				//data array passed
				//view, controller, model, data
				$data=(array_key_exists("data", $this->_route)&&(is_array($this->_route["data"]))) ? $this->_route["data"] : [];
				//check and load one model
				if(array_key_exists("model", $this->_route)) {
					$this->_app->getModelHandler()->loadModel($this->_route["model"]);
				}
				//check and load multiple models
				if(array_key_exists("models", $this->_route)) {
					foreach ($this->_route["models"] as $model) {
						$this->_app->getModelHandler()->loadModel($model);
					}
				}
				//check for controller
				$controller_found=false;
				if(array_key_exists("controller", $this->_route)) {
					$this->_app->getControllerHandler()->loadController($this->_route["controller"], "xTend", $data);
					$controller_found=true;
				}
				//check for multiple controllers
				if(array_key_exists("controllers", $this->_route)) {
					foreach ($this->_route["controllers"] as $controller) {
						$this->_app->getControllerHandler()->loadController($controller, "xTend", $data);
					}
					$controller_found=true;
				}
				//check for view
				//For views, don't pass data, when a controller already has the data
				//No need for duplicate data
				if(array_key_exists("view", $this->_route)) {
					$this->_app->getViewHandler()->loadView($this->_route["view"], ($controller_found) ? [] : $data);
				}
				//check for multiple views
				if(array_key_exists("views", $this->_route)) {
					foreach ($this->_route["views"] as $view) {
						$this->_app->getViewHandler()->loadView($view, ($controller_found) ? [] : $data);
					}
				}
			} else return false;
			return true;
		}

		public function isMatch($request) {
			if(is_string($this->_handle)) {
				//ignore starting and trailing slashes
				$ex_request = explode("/", trim($request, "/"));
				$ex_handle = explode("/", $this->_handle);
				//if the amount of parts dont comply just, end
				if(count($ex_request)!=count($ex_handle)) return false;
				//check all parts of the handle and see whether they match up  to the request
				$ex_count=count($ex_handle); $rx_matches;
				for($i=0;$i<$ex_count;$i++) {
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
							if($qm_pos!==false)
								$request_part=substr($request_part, 0, $qm_pos);
						}
					}
					//check up
					//most complicated structure -> regexed URL variable
					if(preg_match("/^(rx)(\{)([a-zA-Z0-9_]+)(\})(\{)(.*)(\})$/", $handle_part, $rx_matches)&&
						preg_match("/".$rx_matches[6]."/", $request_part)) {
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
						($request_part==$handle_part)) /*they just equal each other*/) {
						//if all of te above fails, return false
						return false;
					}
				}
				//set the route on the UrlHandler
				$this->_app->getUrlHandler()->setRoute($this);
				return true;
			}
			return false;
		}
	}