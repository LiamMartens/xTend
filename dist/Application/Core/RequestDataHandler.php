<?php
    namespace xTend\Core;
    class RequestDataHandler {
        private $_app;
        private $_get;
        private $_post;
        private $_data;
        public function __construct($app) {
            $this->_app = $app;
            $this->_get = [];
            $this->_post = [];
            $this->_data = [];
        }

        public function get() { return $this->_get; }
        public function post() { return $this->_post; }
        public function data() { return $this->_data; }

        private function parseGet() {
            if((count($_GET)==0)&&($this->_app->getUrlHandler()->getMethod()=="GET")) {
                $qm_pos = strpos($this->_app->getUrlHandler()->getRequest(), "?");
                if($qm_pos!==false) parse_str(substr($this->_app->getUrlHandler()->getRequest(), $qm_pos+1), $this->_get);
            } else { $this->_get = $_GET; }
        }
        private function parsePost() {
            if((count($_POST)==0)&&($this->_app->getUrlHandler()->getMethod()=="POST")) {
                //assuming its json
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);
                $this->_post = ($data===null) ? $input : $data;
            } else { $this->_post = $_POST; }
        }
        private function parseData() {
            $data = Session::get(session_id().'-xtend-data', false);
            if($data!==false) {
                $this->_data = json_decode($data, true);
                //remove 1 from ttl
                $ttl = intval(Session::get(session_id().'-xtend-ttl', 0)) - 1;
                if($ttl<0) {
                    Session::remove(session_id().'-xtend-data');
                } else { Session::set(session_id().'-xtend-ttl', $ttl); }
            }
        }
        public function parse() {
            $this->parseGet();
            $this->parsePost();
            $this->parseData();
        }
    }
