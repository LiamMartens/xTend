<?php
    namespace xTend\Objects;
    /**
    * The Request object handles the current
    * request
    */
    class Request {
        /** @var xTend\Core\App Current application */
        private $_app;
        /** @var string Holds the Request method */
        private $_method;
        /** @var string Holdes the Request path */
        private $_path;
        
        /**
        * @param xTend\Core\App $app
        * @param string $method
        * @param string $path
        */
        public function __construct($app, $method, $path) {
            $this->_app = $app;
            $this->_method = $method;
            $this->_path = $path;
        }
        /**
        * Returns the current method, GET POST OPTIONS PATCH DELETE
        */
        public function getMethod() {
            return $this->_method;
        }

        /**
        * Returns the scheme (http - https)
        */
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

        /**
        * Returns GET data
        *
        * @return array
        */
        public function get() { return $this->_app->getRequestDataHandler()->_get(); }

        /**
        * Returns POST data
        *
        * @return array
        */
        public function post() { return $this->_app->getRequestDataHandler()->_post(); }

        /**
        * Returns passed navigation data
        *
        * @return array
        */
        public function data() { return $this->_app->getRequestDataHandler()->_data(); }


        /**
        * Sets the content type
        *
        * @param string $type
        */
        public function setContentType($type) {
            $this->_app->getRequestHandler()->setContentType($type);
        }
    }
