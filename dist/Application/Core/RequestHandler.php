<?php
    namespace xTend\Core;
    use \xTend\Objects\Request;
    class RequestHandler {
        private $_app;
        private $_request;
        private $_contenTypes=[];

        /**
        * @param xTend\Core\App
        */
        public function __construct($app) {
            $this->_app = $app;
        }

        /**
        * Registers a new content type for setting headers
        *
        * @param string $ext
        * @param string $mime
        */
        public function registerContentType($ext, $mime) { $this->_contenTypes[$ext] = $mime; }

        /**
        * Sets the conent type header
        *
        * @param string $type
        *
        * @return string
        */
        public function setContentType($type) {
            $ct = $type;
            if(isset($this->_contenTypes[$type])) {
                $ct=$this->_contenTypes[$ct];
            }
            $this->_contenTypes = $ct;
            header("Content-Type: $ct");
            return $ct;
        }

        /**
        * Initializes the current request
        *
        * @param string $method
        * @param string $path
        *
        * @return xTend\Objects\Request
        */
        public function initialize($method, $path) {
            $this->_request = new Request($this->_app, $method, $path);
            return $this->_request;
        }

        /**
        * Returns the current request
        *
        * @return xTend\Objects\Request
        */
        public function getRequest() {
            return $this->_request;
        }
    }
