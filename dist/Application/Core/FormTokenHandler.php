<?php
    namespace xTend\Core;
    class FormTokenHandler {
        private $_app;
        private $_tokens;
        public function __construct($app) {
            $this->_app = $app;
            $this->_tokens = [];
        }

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

        public function persistent($name) {
            if(isset($this->_tokens[$name])) {
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

        public function check($name, $value) {
            $hash = Session::get("token-$name", false);
            if($hash!==false) {
                return password_verify($value, $hash);
            }
            return false;
        }
    }
