<?php
    namespace xTend\Core;
    use \Defuse\Crypto\Crypto as Crypto;
    /**
    * The Cookie class handles
    * getting, removing and setting
    * encrypted cookies
    */
    class Cookie
    {
        /** @var string Encryption key for cookie values */
        private static $_enckey;

        /**
        * Generates a Cookie encryption key
        *
        * @param string $key
        */
        public static function generate($key) {
            self::$_enckey = sha1($_SERVER["HTTP_USER_AGENT"].$_SERVER["REMOTE_ADDR"].$key);
        }

        /**
        * Sets a cookie, while hashing the name and encrypting the value using the key
        *
        * @param mixed $key
        * @param mixed $value
        * @param integer|boolean $time
        * @param string $domain
        */
        public function set($key, $value, $time=false, $domain='/') {
            if($time===false) $time=time()+3600*24; //one day
            try {
                setcookie(sha1($key), Crypto::encrypt($value, self::$_enckey), $time, $domain);
            } catch(\Exception $e) { self::remove($key, $domain); }
        }

        /**
        * Returns a cookie value thus also decrypting it
        *
        * @param mixed $key
        * @param mixed|boolean $default
        * @param string $domain
        *
        * @return mixed
        */
        public static function get($key, $default=false, $domain='/') {
            if(isset($_COOKIE[sha1($key)])) {
                try {
                    return Crypto::decrypt($_COOKIE[sha1($key)], self::$_enckey);
                } catch(\Exception $e) { self::remove($key, $domain); }
            }
            return $default;
        }

        /**
        * Removes a cookie value
        *
        * @param mixed $key
        * @param string $domain
        */
        public static function remove($key, $domain='/') {
            if(isset($_COOKIE[sha1($key)])) {
                self::set($key,null,time()-1,$domain);
                unset($_COOKIE[sha1($key)]);
            }
        }
    }
