<?php
    namespace Application\Core;
    use Application\Blueprints\StaticDataExtension;


    /**
    * The Request object handles the current
    * request
    * url data is handled by BaseDataExtension
    */
    class Request extends StaticDataExtension {
        /** @var string Contains the current http method verb */
        private static $_method=false; // kept seperately for spoofing
        /** @var string Contains the current trimmed path */
        private static $_path=false;
        /** @var array GET data */
        public static $get=[];
        /** @var array POST data */
        public static $post=[];
        /** @var array navigation pass data */
        public static $data=[];
        /** @var Route contains the route that has been executed */
        private static $_route;


        /**
        * Initializes the Request object
        */
        public static function start() {
            // Parse GET variables
            if(count($_GET)==0) {
                self::$get=parse_str(self::query());
            } else { self::$get = $_GET; }
            // Parse POST variables
            if(count($_POST)==0) {
                //assuming its json
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);
                self::$post = ($data===null) ? ($input=='' ? [] : $input) : $data;
            } else { self::$post = $_POST; }
            // Parse DATA
            $data = Session::get('xt-data', false);
            if($data!==false) {
                self::$data = json_decode($data, true);
                Session::remove('xt-data');
            }
        }
        

        /**
        * Gets or sets the route object
        *
        * @param Route:optional $route
        *
        * @return Route
        */
        public static function route($route=null) {
            if($route!==null) {
                self::$_route=$route;
            }
            return self::$_route;
        }


        /**
        * Gets or sets the method
        *
        * @param string:optional $value
        *
        * @return string
        */
        public static function method($value=null) {
            if($value!==null) {
                self::$_method=strtoupper($value);
            } elseif(self::$_method===false) { self::$_method=$_SERVER['REQUEST_METHOD']; }
            return self::$_method;
        }


        /**
        * Gets the path
        *
        * @return string
        */
        public static function path() {
            if(self::$_path===false) {
                $location=trim(App::location(), '/');
                if(($location!='')&&(strrpos($location, '/')!=strlen($location)-1)) { $location.='/'; }
                $location=str_replace('/', '\/', $location);
                $rx='(?:(?:^('.$location.')index\.php$)|(?:^('.$location.')index\.php\/))';
                self::$_path=preg_replace('/'.$rx.'/', '$1$2', trim($_SERVER['REQUEST_URI'], '/'));
            }
            return self::$_path;
        }


        public static function url() {
            return self::scheme().'://'.self::host();
        }


        public static function scheme() {
            if(
                (isset($_SERVER['HTTPS'])&&
                (($_SERVER['HTTPS']==='on')||($_SERVER['HTTPS']===true)||($_SERVER['HTTPS']===1)))||
                (isset($_SERVER['HTTP_USESSL'])&&
                (($_SERVER['HTTP_USESSL']==='on')||($_SERVER['HTTP_USESSL']===true)||($_SERVER['HTTP_USESSL']===1)))
            ) {
                return 'https';
            }
            return 'http';
        }


        public static function host() {
            return $_SERVER['HTTP_HOST'];
        }


        public static function port() {
            return intval($_SERVER['SERVER_PORT']);
        }


        public static function query() {
            if(!isset($_SERVER['QUERY_STRING'])) {
                $_SERVER['QUERY_STRING']='';
            }
            return $_SERVER['QUERY_STRING'];
        }
    

        /**
        * Sets the content type
        *
        * @param string $type
        */
        public static function contentType($type) {
            header('Content-Type: '.$type);
        }
    }
