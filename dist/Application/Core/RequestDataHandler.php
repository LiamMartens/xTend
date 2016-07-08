<?php
    namespace xTend\Core;
    /**
    * The RequestDataHandler handles
    * get, post and navigation pass data
    */
    class RequestDataHandler {
        /** @var xTend\Core\App Current application */
        private $_app;
        /** @var array GET data */
        private $_get;
        /** @var array POST data */
        private $_post;
        /** @var array navigation pass data */
        private $_data;

        /**
        * @param xTend\Core\App
        */
        public function __construct($app) {
            $this->_app = $app;
            $this->_get = [];
            $this->_post = [];
            $this->_data = [];
        }

        /**
        * Returns parsed GET data
        *
        * @return array
        */
        public function get() { return $this->_get; }

        /**
        * Returns parsed POST data
        *
        * @return array
        */
        public function post() { return $this->_post; }

        /**
        * Returns passed navigation data
        *
        * @return array
        */
        public function data() { return $this->_data; }

        private function parseGet() {
            if(count($_GET)==0) {
                $request = $_SERVER["REQUEST_URI"];
                $qm_pos = strpos($request, "?");
                if($qm_pos!==false) parse_str(substr($request, $qm_pos+1), $this->_get);
            } else { $this->_get = $_GET; }
        }
        private function parsePost() {
            if(count($_POST)==0) {
                //assuming its json
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);
                $this->_post = ($data===null) ? ($input=='' ? [] : $input) : $data;
            } else { $this->_post = $_POST; }
        }
        private function parseData() {
            $data = Session::get(session_id().'-xtend-data', false);
            if($data!==false) {
                $this->_data = json_decode($data, true);
                Session::remove(session_id().'-xtend-data');
            }
        }
        public function parse() {
            $this->parseGet();
            $this->parsePost();
            $this->parseData();
        }
    }
