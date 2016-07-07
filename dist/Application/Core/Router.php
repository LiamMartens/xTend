<?php
	namespace xTend\Core;
	use xTend\Objects\Route as Route;
	class Router
	{
		private $_default;
		private $_home;
		private $_post;
		private $_get;
        private $_put;
        private $_delete;
		private $_patch;
		private $_options;
		private $_any;
		private $_error;
		private $_aliases;

		private $_app;
		public function __construct($app) {
			$this->_app = $app;
			//init empty
			$this->_post=[];
			$this->_get=[];
            $this->_put=[];
            $this->_delete=[];
			$this->_patch=[];
			$this->_options=[];
			$this->_any=[];
			$this->_error=[];
			$this->_aliases=[];
		}

		public function getRoutes() {
			return array_merge([$this->_home],
								$this->_post,
								$this->_get,
								$this->_delete,
								$this->_any,
								$this->_error);
		}

		public function getRouteByAlias($alias) {
			if(array_key_exists($alias, $this->_aliases))
				return $this->_aliases[$alias];
			return false;
		}

		public function getPostRoute($handle) {
			if(array_key_exists($handle, $this->_post))
				return $this->_post[$handle];
			return false;
		}

		public function getGetRoute($handle) {
			if(array_key_exists($handle, $this->_get))
				return $this->_get[$handle];
			return false;
		}

        public function getPutRoute($handle) {
			if(array_key_exists($handle, $this->_put))
				return $this->_put[$handle];
			return false;
        }

        public function getDeleteRoute($handle) {
			if(array_key_exists($handle, $this->_delete))
				return $this->_delete[$handle];
			return false;
        }

		public function getPatchRoute($handle) {
			if(array_key_exists($handle, $this->_patch)) {
				return $this->_patch[$handle];
			}
			return false;
		}

		public function getOptionsRoute($handle) {
			if(array_key_exists($handle, $this->_options)) {
				return $this->_options[$handle];
			}
			return false;
		}

		public function getAnyRoute($handle) {
			if(array_key_exists($handle, $this->_any))
				return $this->_any[$handle];
			return false;
		}

		public function getErrorRoute($handle) {
			if(array_key_exists($handle, $this->_error))
				return $this->_error[$handle];
			return false;
		}

		private function add(&$routes, $handle, $route=false, $alias=false) {
			//you can either pass an actual handle as the handle
			//or directly pass a route object as the handle
			//ignoring the route and alias parameters completely
			$h; if(is_string($handle)&&($route!==false)) {
				$h = new Route($this->_app, $handle, $route, $alias);
			} elseif($handle instanceof Route) { $h=$handle; }
			//add route to the post
			$routes[$h->getHandle()]=$h;
			//add to aliases if there is any
			if($h->getAlias()!==false) {
				$this->_aliases[$h->getAlias()]=$h;
			}
			//return Route object
			return $h;
		}

		public function post($handle, $route=false, $alias=false) {
			return $this->add($this->_post, $handle, $route, $alias);
		}

		public function get($handle, $route=false, $alias=false) {
			return $this->add($this->_get, $handle, $route, $alias);
		}

        public function put($handle, $route=false, $alias=false) {
			return $this->add($this->_put, $handle, $route, $alias);
        }

        public function delete($handle, $route=false, $alias=false) {
			return $this->add($this->_delete, $handle, $route, $alias);
        }

		public function patch($handle, $route=false, $alias=false) {
			return $this->add($this->_patch, $handle, $route, $alias);
		}

		public function options($handle, $route=false, $alias=false) {
			return $this->add($this->_options, $handle, $route, $alias);
		}

		public function any($handle, $route=false, $alias=false) {
			return $this->add($this->_any, $handle, $route, $alias);
		}

		public function match($methods, $handle, $route=false, $alias=false) {
			foreach ($methods as $method) {
                $method=strtolower($method);
				$this->$method($handle, $route, $alias);
			}
		}

		public function error($handle, $route=false, $alias=false) {
			//here the handler should be an errorcode
			//you can either pass an actual handle as the handle
			//or directly pass a route object as the handle
			//ignoring the route and alias parameters completely
			$h; if(is_numeric($handle)&&($route!==false)) {
				$h = new Route($this->_app, $handle, $route, $alias);
			} elseif($handle instanceof Route) { $h=$handle; }
			elseif($this->_app->getStatusCodeHandler()->findStatus($handle) instanceof StatusCode) {
				$h = new Route($this->_app, $this->_app->getStatusCodeHandler()->findStatus($handle)->getCode(), $route, $alias); }
			//add route to the error list
			$this->_error[$h->getHandle()]=$h;
			//add to aliases if there is any
			if($h->getAlias()!==false) {
				$this->_aliases[$h->getAlias()]=$h;
			}
			//return Route object
			return $h;
		}

		public function def($route) {
			//the default should always be a route, not a handle or route object
			$this->_default = new Route($this->_app, false, $route, false);
			return $this->_default;
		}

		public function home($route) {
			$home_handle = "";
			$forw_pos = strpos($this->_app->getUrl(), "/", 8);
			if($forw_pos!==false) { $home_handle=substr($this->_app->getUrl(), $forw_pos+1); }
			//the home should always be a route not a handle or route object
			$this->_home = new Route($this->_app, $home_handle, $route, false);
			return $this->_home;
		}

		//route restrction
		//a restriction function should always return a boolean (or 0 or 1)
		//routes will only be defined when restriction func returns true
		public function restrict($rest, $routes) {
			if((is_callable($rest)&&is_callable($routes)&&($rest($this->_app)==true))||
				(($rest===true)&&(is_callable($routes)))) {
				$routes($this->_app);
				return true;
			}
			return false;
		}

		public function throwError($error) {
			$code=$error;
			if($error instanceof StatusCode) { $code=$error->getCode(); }
			//find the error route if set
			if(array_key_exists($code, $this->_error)) {
				$this->_error[$code]->execute();
				return true;
			}
			return false;
		}

		public function execute() {
			$request = trim($_SERVER["REQUEST_URI"], "/");
			//execute data handler parser
			$this->_app->getRequestDataHandler()->parse();
            //allow method spoofing
			$post=$this->_app->getRequestDataHandler()->post();
			if(array_key_exists('_method', $post)) {
				$_SERVER['REQUEST_METHOD']=strtoupper($post['_method']); }
            $this->_app->getRequestHandler()->initialize($_SERVER["REQUEST_METHOD"], $request);
			//check home route
			if(isset($this->_home)&&$this->_home->isMatch($request)) {
				$this->_home->execute(); return true;
			}
			//check any routes
			foreach ($this->_any as $handle => $route_obj) {
				if($route_obj->isMatch($request)) {
					$route_obj->execute(); return true;
				}
			}
			//check for method routes | POST or GET
			$relevant_requests;
			if($_SERVER["REQUEST_METHOD"]=="POST") {
				$relevant_requests = $this->_post;
			} elseif($_SERVER["REQUEST_METHOD"]=="GET") {
				$relevant_requests = $this->_get;
			} elseif($_SERVER["REQUEST_METHOD"]=="PUT") {
				$relevant_requests = $this->_put;
            } elseif($_SERVER["REQUEST_METHOD"]=="DELETE") {
				$relevant_requests = $this->_delete;
            } elseif($_SERVER["REQUEST_METHOD"]=="PATCH") {
				$relevant_requests = $this->_patch;
			} elseif($_SERVER["REQUEST_METHOD"]=="OPTIONS") {
				$relevant_requests = $this->_options;
			}
			//check the releavant requests
			foreach ($relevant_requests as $handle => $route_obj) {
				if($route_obj->isMatch($request)) {
					$route_obj->execute(); return true;
				}
			}
			//no routes have been executed here
			//check for error page
			if(!$this->_app->throwError(0x0194)) {
				//check for default
				if(isset($this->_default)) {
					$this->_default->execute();
					return true;
				}
			}
			return false;
		}
	}
