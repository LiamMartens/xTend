<?php
    namespace Application\Core;
    use \Defuse\Crypto\Crypto as Crypto;

    /**
    * The Session class handles setting and
    * encrypting Session values
    */
    class Session {
        /** @var string Session encryption key */
        private static $_enckey;

        /**
        * @param mixed $key
        */
        public static function generate($key) {
            self::$_enckey = sha1(session_name().session_id().$key);
        }

        /**
        * @param mixed $key
        */
        public static function remove($key) {
            if(isset($_SESSION[sha1($key)]))
                unset($_SESSION[sha1($key)]);
        }

        /**
        * @param mixed $key
        * @param mixed $value
        */
        public static function set($key, $value) {
            try {
                $_SESSION[sha1($key)] = Crypto::encrypt($value, self::$_enckey);
            } catch(\Exception $e) { self::remove($key); }
        }

        /**
        * @param mixed $key
        * @param mixed $default
        *
        * @return mixed
        */
        public static function get($key, $default=false) {
            if(isset($_SESSION[sha1($key)])) {
                try {
                    return Crypto::decrypt($_SESSION[sha1($key)], self::$_enckey);
                } catch (\Exception $e) { self::remove($key); }
            }
            return $default;
        }
    }
