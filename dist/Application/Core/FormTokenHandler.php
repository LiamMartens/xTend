<?php
    namespace xTend\Core;
    class FormTokenHandler {
        private $_app;
        public function __construct($app) {
            $this->_app = $app;
        }

        public function generate($name) {
            //generate token
            $token = hash("sha512", random_bytes(16));
            //generate pass hash
            $hash = password_hash($token);
            //set session
            Session::set("token.$name", $hash);
            //return original token
            return $token;
        }

        public function check($name, $value) {
            $hash = Session::get("token.$name", false);
            if($hash!==false) {
                return password_verify($value, $hash);
            }
            return false;
        }
    }
