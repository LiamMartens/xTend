<?php
    namespace xTend\Objects;
    class Request {
        private $_app;
        private $_method;
        private $_path;
        public function __construct($app, $method, $path) {
            $this->_app = $app;
            $this->_method = $method;
            $this->_path = $path;
        }

        public function getMethod() {
            return $this->_method;
        }

        public function getScheme() {
            if($_SERVER["HTTPS"]=='on') {
                return 'https';
            }
            return 'http';
        }

        public function getHost() {
            return $_SERVER["HTTP_HOST"];
        }

        public function getPort() {
            return intval($_SERVER["SERVER_PORT"]);
        }

        public function getQuery() {
            return $_SERVER["QUERY_STRING"];
        }

        public function getPath() {
            return $this->_path;
        }

        public function setContentType($type) {
            $this->_app->getRequestHandler()->setContentType($type);
        }
    }
