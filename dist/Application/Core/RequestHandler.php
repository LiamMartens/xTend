<?php
    namespace xTend\Core;
    use \xTend\Objects\Request;
    class RequestHandler {
        private $_app;
        private $_request;
        private $_contenTypes=[];
        public function __construct($app) {
            $this->_app = $app;
        }

        public function registerContentType($ext, $mime) { $this->_contenTypes[$ext] = $mime; }
        public function setContentType($type) {
            $ct = $type;
            if(isset($this->_contenTypes[$type])) {
                $ct=$this->_contenTypes[$ct];
            }
            $this->_contenTypes = $ct;
            header("Content-Type: $ct");
            return $ct;
        }

        public function initialize($method, $path) {
            $this->_request = new Request($this->_app, $method, $path);
        }

        public function getRequest() {
            return $this->_request;
        }
    }
