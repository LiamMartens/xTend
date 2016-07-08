<?php
    namespace xTend\Core;
    use xTend\Objects\Route;
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
        /**
        * @param xTend\Core\App
        */
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

        /**
        * Returns all registered routes
        *
        * @return array
        */
        public function getRoutes() {
            return array_merge([$this->_home],
                                $this->_post,
                                $this->_get,
                                $this->_delete,
                                $this->_any,
                                $this->_error);
        }

        /**
        * @param string $alias
        *
        * @return xTend\Objects\Route | boolean
        */
        public function getRouteByAlias($alias) {
            if(array_key_exists($alias, $this->_aliases))
                return $this->_aliases[$alias];
            return false;
        }

        /**
        * Returns a registered POST route
        *
        * @param string $handle
        *
        * @return xTend\Objects\Route | boolean
        */
        public function getPostRoute($handle) {
            if(array_key_exists($handle, $this->_post))
                return $this->_post[$handle];
            return false;
        }

        /**
        * Returns a registered GET route
        *
        * @param string $handle
        *
        * @return xTend\Objects\Route | boolean
        */
        public function getGetRoute($handle) {
            if(array_key_exists($handle, $this->_get))
                return $this->_get[$handle];
            return false;
        }

        /**
        * Returns a registered PUT route
        *
        * @param string $handle
        *
        * @return xTend\Objects\Route | boolean
        */
        public function getPutRoute($handle) {
            if(array_key_exists($handle, $this->_put))
                return $this->_put[$handle];
            return false;
        }

        /**
        * Returns a registered DELETE route
        *
        * @param string $handle
        *
        * @return xTend\Objects\Route | boolean
        */
        public function getDeleteRoute($handle) {
            if(array_key_exists($handle, $this->_delete))
                return $this->_delete[$handle];
            return false;
        }

        /**
        * Returns a registered PATCH route
        *
        * @param string $handle
        *
        * @return xTend\Objects\Route | boolean
        */
        public function getPatchRoute($handle) {
            if(array_key_exists($handle, $this->_patch)) {
                return $this->_patch[$handle];
            }
            return false;
        }

        /**
        * Returns a registered OPTIONS route
        *
        * @param string $handle
        *
        * @return xTend\Objects\Route | boolean
        */
        public function getOptionsRoute($handle) {
            if(array_key_exists($handle, $this->_options)) {
                return $this->_options[$handle];
            }
            return false;
        }

        /**
        * Returns a registered any route
        *
        * @param string $handle
        *
        * @return xTend\Objects\Route | boolean
        */
        public function getAnyRoute($handle) {
            if(array_key_exists($handle, $this->_any))
                return $this->_any[$handle];
            return false;
        }

        /**
        * Returns a registered error route
        *
        * @param string $handle
        *
        * @return xTend\Objects\Route | boolean
        */
        public function getErrorRoute($handle) {
            if(array_key_exists($handle, $this->_error))
                return $this->_error[$handle];
            return false;
        }

        /**
        * Registers a new route
        *
        * @param array reference $routes
        * @param string|xTend\Objects\Route $handle
        * @param mixed $route
        * @param string|boolean $alias
        *
        * @return xTend\Objects\Route
        */
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

        /**
        * Registers a new POST route
        *
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        *
        * @return xTend\Objects\Route
        */
        public function post($handle, $route=false, $alias=false) {
            return $this->add($this->_post, $handle, $route, $alias);
        }

        /**
        * Registers a new GET route
        *
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        *
        * @return xTend\Objects\Route
        */
        public function get($handle, $route=false, $alias=false) {
            return $this->add($this->_get, $handle, $route, $alias);
        }

        /**
        * Registers a new PUT route
        *
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        *
        * @return xTend\Objects\Route
        */
        public function put($handle, $route=false, $alias=false) {
            return $this->add($this->_put, $handle, $route, $alias);
        }

        /**
        * Registers a new DELETE route
        *
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        *
        * @return xTend\Objects\Route
        */
        public function delete($handle, $route=false, $alias=false) {
            return $this->add($this->_delete, $handle, $route, $alias);
        }

        /**
        * Registers a new PATCH route
        *
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        *
        * @return xTend\Objects\Route
        */
        public function patch($handle, $route=false, $alias=false) {
            return $this->add($this->_patch, $handle, $route, $alias);
        }

        /**
        * Registers a new OPTIONS route
        *
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        *
        * @return xTend\Objects\Route
        */
        public function options($handle, $route=false, $alias=false) {
            return $this->add($this->_options, $handle, $route, $alias);
        }

        /**
        * Registers a new any route
        *
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        *
        * @return xTend\Objects\Route
        */
        public function any($handle, $route=false, $alias=false) {
            return $this->add($this->_any, $handle, $route, $alias);
        }

        /**
        * Registers new routes according to the methods
        *
        * @param array $methods
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        */
        public function match($methods, $handle, $route=false, $alias=false) {
            foreach ($methods as $method) {
                $method=strtolower($method);
                $this->$method($handle, $route, $alias);
            }
        }

        /**
        * Registers error routes
        *
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        *
        * @return xTend\Objects\Route
        */
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

        /**
        * Registers a default route
        *
        * @param mixed $route
        *
        * @return xTend\Objects\Route
        */
        public function def($route) {
            //the default should always be a route, not a handle or route object
            $this->_default = new Route($this->_app, false, $route, false);
            return $this->_default;
        }

        /**
        * Sets a home route
        *
        * @param mixed $route
        *
        * @return xTend\Objects\Route
        */
        public function home($route) {
            $home_handle = "";
            $forw_pos = strpos($this->_app->getUrl(), "/", 8);
            if($forw_pos!==false) { $home_handle=substr($this->_app->getUrl(), $forw_pos+1); }
            //the home should always be a route not a handle or route object
            $this->_home = new Route($this->_app, $home_handle, $route, false);
            return $this->_home;
        }

        /**
        * Adds route restrictions
        *
        * @param function $rest
        * @param function $routes
        *
        * @return boolean
        */
        public function restrict($rest, $routes) {
            if((is_callable($rest)&&is_callable($routes)&&($rest($this->_app)==true))||
                (($rest===true)&&(is_callable($routes)))) {
                $routes($this->_app);
                return true;
            }
            return false;
        }

        /**
        * Throws an error route
        *
        * @param integer|xTend\Core\StatusCode
        *
        * @return boolean
        */
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

        /**
        * Executes the router
        *
        * @return boolean
        */
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
