<?php
	namespace xTend
	{
		class Router
		{
			private $_default;
			private $_home;
			private $_post;
			private $_get;
			private $_any;
			private $_error;
			private $_aliases;

			private $_app;
			public function __construct($app) {
				$this->_app = $app;
				//init empty
				$this->_post=[];
				$this->_get=[];
				$this->_any=[];
				$this->_error=[];
				$this->_aliases=[];
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

			public function post($handle, $route=false, $alias=false) {
				//you can either pass an actual handle as the handle
				//or directly pass a route object as the handle
				//ignoring the route and alias parameters completely
				$h; if(is_string($handle)&&($route!==false)) {
					$h = new Route($this->_app, $handle, $route, $alias);
				} elseif($handle instanceof Route) { $h=$handle; }
				//add route to the post
				$this->_post[$h->getHandle()]=$h;
				//add to aliases if there is any
				if($h->getAlias()!==false) {
					$this->_aliases[$h->getAlias()]=$h;
				}
				//return Route object
				return $h;
			}

			public function get($handle, $route=false, $alias=false) {
				//you can either pass an actual handle as the handle
				//or directly pass a route object as the handle
				//ignoring the route and alias parameters completely
				$h; if(is_string($handle)&&($route!==false)) {
					$h = new Route($this->_app, $handle, $route, $alias);
				} elseif($handle instanceof Route) { $h=$handle; }
				//add route to the get
				$this->_get[$h->getHandle()]=$h;
				//add to aliases if there is any
				if($h->getAlias()!==false) {
					$this->_aliases[$h->getAlias()]=$h;
				}
				//return Route object
				return $h;
			}

			public function any($handle, $route=false, $alias=false) {
				//you can either pass an actual handle as the handle
				//or directly pass a route object as the handle
				//ignoring the route and alias parameters completely
				$h; if(is_string($handle)&&($route!==false)) {
					$h = new Route($this->_app, $handle, $route, $alias);
				} elseif($handle instanceof Route) { $h=$handle; }
				//add route to the any
				$this->_any[$h->getHandle()]=$h;
				//add to aliases if there is any
				if($h->getAlias()!==false) {
					$this->_aliases[$h->getAlias()]=$h;
				}
				//return Route object
				return $h;
			}

			public function error($handle, $route=false, $alias=false) {
				//here the handler should be an errorcode
				//you can either pass an actual handle as the handle
				//or directly pass a route object as the handle
				//ignoring the route and alias parameters completely
				$h; if(is_numeric($handle)&&($route!==false)) {
					$h = new Route($this->_app, $handle, $route, $alias);
				} elseif($handle instanceof Route) { $h=$handle; }
				elseif($this->_app->getErrorCodeHandler()->findError($handle) instanceof ErrorCode) {
					$h = new Route($this->_app, $this->_app->getErrorCodeHandler()->findError($handle)->getCode(), $route, $alias); }
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
				$forw_pos = strpos($this->_app->getUrl(), "/", 7);
				if($forw_pos!==false) { $home_handle=substr($this->_app->getUrl(), $forw_pos+1); }
				//the home should always be a route not a handle or route object
				$this->_home = new Route($this->_app, $home_handle, $route, false);
				return $this->_home;
			}

			//route restrction
			//a restriction function should always return a boolean (or 0 or 1)
			//routes will only be defined when restriction func returns true
			public function restrict($rest, $routes) {
				if(is_callable($rest)&&is_callable($routes)&&($rest()==true)) {
					$routes();
					return true;
				}
				return false;
			}

			public function throwError($error) {
				$code=$error;
				if($error instanceof ErrorCode) { $code=$error->getCode(); }
				//find the error route if set
				if(array_key_exists($code, $this->_error)) {
					$this->_error[$code]->execute();
					return true;
				}
				return false;
			}

			public function execute() {
				$request = trim($_SERVER["REQUEST_URI"], "/");
				$this->_app->getUrlHandler()->setRequest($request);
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
					$this->_app->getUrlHandler()->setMethod("POST");
				} elseif($_SERVER["REQUEST_METHOD"]=="GET") {
					$relevant_requests = $this->_get;
					$this->_app->getUrlHandler()->setMethod("GET");
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
	}