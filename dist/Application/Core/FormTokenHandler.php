<?php
    namespace xTend\Core;

    /**
    * The FormTokenHandler handles
    * CSRF token
    */
    class FormTokenHandler {
        /** @var xTend\Core\App Current application */
        private $_app;
        /** @var array Contains all persistent tokens */
        private $_tokens;

        /**
        * @param xTend\Core\App
        */
        public function __construct($app) {
            $this->_app = $app;
            $this->_tokens = [];
        }

        /**
        * Generates a form token with name
        *
        * @param string $name
        *
        * @return string
        */
        public function generate($name) {
            //generate token
            $token = hash("sha512", random_bytes(16));
            //generate pass hash
            $hash = password_hash($token, PASSWORD_DEFAULT);
            //set session
            Session::set("token-$name", $hash);
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
        public function persistent($name) {
            if(!isset($this->_tokens[$name])) {
                //generate token
                $token = hash("sha512", random_bytes(16));
                //generate pass hash
                $hash = password_hash($token, PASSWORD_DEFAULT);
                //set token and session
                $this->_tokens[$name] = $token;
                Session::set("token-$name", $hash);
            }
            return $this->_tokens[$name];
        }

        /**
        * Checks whether formtokens match
        *
        * @param string $name
        * @param string $value
        *
        * @return boolean
        */
        public function check($name, $value) {
            $hash = Session::get("token-$name", false);
            if($hash!==false) {
                return password_verify($value, $hash);
            }
            return false;
        }
    }
