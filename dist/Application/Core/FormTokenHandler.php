<?php
    namespace Application\Core;

    /**
    * The FormTokenHandler handles
    * CSRF token
    */
    class FormTokenHandler {
        /** @var array Contains all persistent tokens */
        private static $_tokens=[];

        /**
        * Generates a form token with name
        *
        * @param string $name
        *
        * @return string
        */
        public static function generate($name) {
            //generate token
            $token = hash('sha512', random_bytes(16));
            //generate pass hash
            $hash = password_hash($token, PASSWORD_DEFAULT);
            //set session
            Session::set('token-'.$name, $hash);
            //return original token
            return $token;
        }

        /**
        * Generates persistent token with name
        *
        * @param string $name
        *
        * @return string
        */
        public static function persistent($name) {
            if(!isset($this->_tokens[$name])) {
                //generate token
                $token = hash('sha512', random_bytes(16));
                //generate pass hash
                $hash = password_hash($token, PASSWORD_DEFAULT);
                //set token and session
                self::$_tokens[$name] = $token;
                Session::set('token-'.$name, $hash);
            }
            return self::$_tokens[$name];
        }

        /**
        * Checks whether formtokens match
        *
        * @param string $name
        * @param string $value
        *
        * @return boolean
        */
        public static function check($name, $value) {
            $hash = Session::get('token-'.$name, false);
            if($hash!==false) {
                return password_verify($value, $hash);
            }
            return false;
        }
    }
