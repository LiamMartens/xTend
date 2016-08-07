<?php
    namespace Application\Core;
    use Application\Objects\Router\Route;

    /**
    * The Router handles the routes
    */
    class Router {
        /** @var xTend\Objects\Route Default route */
        private static $_default;
        /** @var xTend\Objects\Route Home route */
        private static $_home;
        /** @var array Contains the POST routes */
        private static $_post=[];
        /** @var array Contains the GET routes */
        private static $_get=[];
        /** @var array Contains the PUT routes */
        private static $_put=[];
        /** @var array Contains the DELETE routes */
        private static $_delete=[];
        /** @var array Contains the PATCH routes */
        private static $_patch=[];
        /** @var array Contains the OPTIONS routes */
        private static $_options=[];
        /** @var array Contains the any routes */
        private static $_any=[];
        /** @var array Contains the error routes */
        private static $_error=[];
        /** @var array Contains the aliases of the routes */
        private static $_aliases=[];

        /**
        * Returns all registered routes
        *
        * @return array
        */
        public static function all() {
            return array_merge([self::$_home],
                                self::$_post,
                                self::$_get,
                                self::$_delete,
                                self::$_any,
                                self::$_error);
        }

        /**
        * @param string $alias
        *
        * @return xTend\Objects\Route | boolean
        */
        public static function alias($alias) {
            if(isset(self::$_aliases[$alias]))
                return self::$_aliases[$alias];
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
        private static function add(&$routes, $handle, $route=false, $alias=false, $override=false) {
            //you can either pass an actual handle as the handle
            //or directly pass a route object as the handle
            //ignoring the route and alias parameters completely
            $h; if(is_string($handle)&&($route!==false)) {
                $h = new Route($handle, $route, $alias);
            } elseif($handle instanceof Route) { $h=$handle; }
            if(($override===true)||(!isset($routes[$h->handle()]))) {
                //add route to the post
                $routes[$h->handle()]=$h;
                //add to aliases if there is any
                if($h->alias()!==false) {
                    self::$_aliases[$h->alias()]=$h;
                }
            } elseif(isset($routes[$h->handle()])) { $h=$routes[$h->handle()]; }
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
        public static function post($handle, $route=false, $alias=false, $override=false) {
            return self::add(self::$_post, $handle, $route, $alias, $override);
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
        public static function get($handle, $route=false, $alias=false, $override=false) {
            return self::add(self::$_get, $handle, $route, $alias, $override);
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
        public static function put($handle, $route=false, $alias=false, $override=false) {
            return self::add(self::$_put, $handle, $route, $alias, $override);
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
        public static function delete($handle, $route=false, $alias=false, $override=false) {
            return self::add(self::$_delete, $handle, $route, $alias, $override);
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
        public static function patch($handle, $route=false, $alias=false, $override=false) {
            return self::add(self::$_patch, $handle, $route, $alias, $override);
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
        public static function options($handle, $route=false, $alias=false, $override=false) {
            return self::add(self::$_options, $handle, $route, $alias, $override);
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
        public static function any($handle, $route=false, $alias=false, $override=false) {
            return self::add(self::$_any, $handle, $route, $alias, $override);
        }

        /**
        * Registers new routes according to the methods
        *
        * @param array $methods
        * @param string|xTend\Objects\Route
        * @param mixed $route
        * @param string|boolean $alias
        */
        public static function match($methods, $handle, $route=false, $alias=false, $override=false) {
            foreach ($methods as $method) {
                $method=strtolower($method);
                self::$method($handle, $route, $alias, $override);
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
        public static function error($handle, $route=false, $alias=false, $override=false) {
            //here the handler should be an errorcode
            //you can either pass an actual handle as the handle
            //or directly pass a route object as the handle
            //ignoring the route and alias parameters completely
            $h; if(is_numeric($handle)&&($route!==false)) {
                $h = new Route($handle, $route, $alias);
            } elseif($handle instanceof Route) { $h=$handle; }
            elseif(StatusCodeHandler::find($handle) instanceof StatusCode) {
                $h = new Route(StatusCodeHandler::find($handle)->code(), $route, $alias); }
            if(($override===true)||(!isset($routes[$h->handle()]))) {
                //add route to the error list
                self::$_error[$h->handle()]=$h;
                //add to aliases if there is any
                if($h->alias()!==false) {
                    self::$_aliases[$h->alias()]=$h;
                }
            } elseif(isset($routes[$h->handle()])) {
                $h=$routes[$h->handle()];
            }
            //return Route object
            return $h;
        }

        /**
        * Registers or returns the default route
        *
        * @param mixed $route
        *
        * @return xTend\Objects\Route
        */
        public static function def($route=null) {
            //the default should always be a route, not a handle or route object
            if($route!==null) {
                self::$_default = new Route(false, $route, false);
            }
            return self::$_default;
        }

        /**
        * Sets a home route
        *
        * @param mixed $route
        *
        * @return xTend\Objects\Route
        */
        public static function home($route=null) {
            if($route!==null) {
                //the home should always be a route not a handle or route object
                self::$_home = new Route(App::location(), $route, false);
            }
            return self::$_home;
        }

        /**
        * Adds route restrictions
        *
        * @param function $rest
        * @param function $routes
        *
        * @return boolean
        */
        public static function restrict($rest, $routes) {
            if((is_callable($rest)&&is_callable($routes)&&($rest()==true))||
                (($rest===true)&&(is_callable($routes)))) {
                $routes();
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
        public static function throw($error) {
            $code=$error;
            if($error instanceof StatusCode) { $code=$error->code(); }
            //find the error route if set
            if(isset(self::$_error[$code])) {
                self::$_error[$code]->execute();
                return true;
            }
            return false;
        }

        /**
        * Executes the router
        *
        * @return boolean
        */
        public static function start() {
            $request = Request::path();
            //allow method spoofing
            $post=Request::$post;
            if(isset($post['_method'])) {
                Request::method($post['_method']); }
            //check home route
            if(isset(self::$_home)&&self::$_home->match($request)) {
                self::$_home->execute(); return true;
            }
            //check any routes
            foreach (self::$_any as $handle => $route_obj) {
                if($route_obj->match($request)) {
                    $route_obj->execute(); return true;
                }
            }
            //check for method routes | POST or GET
            $relevant_requests;
            if(Request::method()=="POST") {
                $relevant_requests = self::$_post;
            } elseif(Request::method()=="GET") {
                $relevant_requests = self::$_get;
            } elseif(Request::method()=="PUT") {
                $relevant_requests = self::$_put;
            } elseif(Request::method()=="DELETE") {
                $relevant_requests = self::$_delete;
            } elseif(Request::method()=="PATCH") {
                $relevant_requests = self::$_patch;
            } elseif(Request::method()=="OPTIONS") {
                $relevant_requests = self::$_options;
            }
            //check the releavant requests
            foreach ($relevant_requests as $handle => $route_obj) {
                if($route_obj->match($request)) {
                    $route_obj->execute(); return true;
                }
            }
            //no routes have been executed here
            //check for error page
            if(isset(self::$_default)) {
                self::$_default->execute();
                return true;
            }
            App::throw(0x0194);
            return false;
        }
    }
