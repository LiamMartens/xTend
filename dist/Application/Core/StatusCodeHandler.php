<?php
    namespace Application\Core;
    use Application\Objects\StatusCodeHandler\StatusCode;   
    /**
    * The StatusCodeHandler handles registering
    * and managing status codes
    */
    class StatusCodeHandler {
        /** @var array Contains the registered error codes */
        private static $_statusCodes;

        /**
        * Returns all status codes
        *
        * @return array
        */
        public static function all() {
            return self::$_statusCodes;
        }

        /**
        * Finds a status by it's key
        *
        * @param integer|string $key
        *
        * @return xTend\Core\StatusCode
        */
        public static function find($key) {
            if(is_numeric($key)&&(isset(self::$_statusCodes[$key]))) {
                //provided key is numeric -> found it in status code directory itself
                return self::$_statusCodes[$key];
            } elseif(is_string($key)) {
                foreach (self::$_statusCodes as $c) {
                    if($c->match($key)) return $c;
                }
            }
        }

        /**
        * Registers a new status code
        *
        * @param integer $code
        * @param string $name
        * @param string $readable
        *
        * @return xTend\Core\StatusCode
        */
        public static function register($code, $name, $readable = '') {
            if(!is_numeric($code)) { throw self::find(0x0000); }
            if(strlen($name)==0) { throw self::find(0x0001); }
            self::$_statusCodes[$code]=new StatusCode($code,$name,$readable);
        }
    }