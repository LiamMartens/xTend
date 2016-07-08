<?php
    namespace xTend\Core;
    use \Exception as Exception;
    class StatusCode
    {
        protected $_code;
        protected $_name;
        protected $_readableName;

        /*
        * @return integer
        */
        public function getCode() { return $this->_code; }

        /*
        * @return string
        */
        public function getHexCode() { return ("0x".str_pad(strval(dechex($this->getCode())), 4, "0", STR_PAD_LEFT)); }

        /*
        * @return string
        */
        public function getName() { return $this->_name; }

        /*
        * @return string
        */
        public function getReadableName() { return $this->_readableName; }

        /*
        * @return string
        */
        public function getStatus() { return "(".$this->getHexCode()." ".$this->getName().") ".$this->getReadableName(); }

        /*
        * @param string|integer $key
        *
        * @return boolean
        */
        public function isStatus($key) { return (($key===$this->getCode())||($key===$this->getName())||($key==$this->getHexCode())); }

        /*
        * Gets an exception from the statuscode
        *
        * @return Exception
        */
        public function getException() { return new Exception($this->getError(), $this->getCode()); }

        /*
        * @param integer $code
        * @param string $name
        * @param string $readable
        */
        public function __construct($code, $name, $readable="") {
            $this->_code = $code;
            $this->_name = $name;
            $this->_readableName = $readable;
        }
    }
    class StatusCodeHandler
    {
        protected $_statusCodes;

        /*
        * Returns all status codes
        *
        * @return array
        */
        public function getStatusCodes() {
            return $this->_statusCodes;
        }

        /*
        * Finds a status by it's key
        *
        * @param integer|string $key
        *
        * @return xTend\Core\StatusCode
        */
        public function findStatus($key) {
            if(is_numeric($key)&&(array_key_exists($key, $this->_statusCodes))) {
                //provided key is numeric -> found it in status code directory itself
                return $this->_statusCodes[$key];
            } elseif(is_string($key)) {
                foreach ($this->_statusCodes as $c) {
                    if($c->isStatus($key)) return $c;
                }
            }
        }

        /*
        * Registers a new status code
        *
        * @param integer $code
        * @param string $name
        * @param string $readable
        *
        * @return xTend\Core\StatusCode
        */
        public function registerStatusCode($code, $name, $readable = "") {
            if(!is_numeric($code)) { throw $this->findStatus(0x0000)->getException(); }
            if(strlen($name)==0) { throw $this->findStatus(0x0001)->getException(); }
            $this->_statusCodes[$code]=new StatusCode($code,$name,$readable);
        }

        public function __construct() {
            //initialize default status codes
            $this->registerStatusCode(0x0000, "statuscodehandler:invalid-code","Error in StatusCodeHandler: Trying to register invalid status code");
            $this->registerStatusCode(0x0001, "statuscodehandler:invalid-name","Error in StatusCodeHandler: Trying to register invalid status name");
        }
    }
