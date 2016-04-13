<?php
    namespace xTend\Core;
    class RequestDataHandler {
        private $_app;
        private $_get;
        private $_post;
        public function __construct($app) {
            $this->_app = $app;
            $this->_get = [];
            $this->_post = [];
        }

        public function get() { return $this->_get; }
        public function post() { return $this->_post; }

        private function parseGet() {
            if((count($_GET)==0)&&($this->_app->getUrlHandler()->getMethod()=="GET")) {
                $qm_pos = strpos($this->_app->getUrlHandler()->getRequest(), "?");
                if($qm_pos!==false) parse_str(substr($this->_app->getUrlHandler()->getRequest(), $qm_pos+1), $this->_get);
            }
        }
        private function parsePost() {
            if((count($_POST)==0)&&($this->_app->getUrlHandler()->getMethod()=="POST")) {
                //assuming its json
                $this->_post = json_decode(file_get_contents("php://input"), true);
            }
        }
        public function parse() {
            $this->parseGet();
            $this->parsePost();
        }
    }
