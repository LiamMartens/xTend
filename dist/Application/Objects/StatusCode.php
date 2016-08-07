<?php
    namespace Application\Objects\StatusCodeHandler;
    use \Exception;
    class StatusCode {
        /** @var integer Code of the status code */
        protected $_code;
        /** @var string The name of the status */
        protected $_name;
        /** @var string The description of the status */
        protected $_readableName;

        /**
        * @return integer
        */
        public function code() { return $this->_code; }

        /**
        * @return string
        */
        public function hex() { return ("0x".str_pad(strval(dechex($this->code())), 4, "0", STR_PAD_LEFT)); }

        /**
        * @return string
        */
        public function name() { return $this->_name; }

        /**
        * @return string
        */
        public function readable() { return $this->_readableName; }

        /**
        * @return string
        */
        public function status() { return '('.$this->hex().' '.$this->name().') '.$this->readable(); }

        /**
        * @param string|integer $key
        *
        * @return boolean
        */
        public function match($key) { return (($key===$this->code())||($key===$this->name())||($key==$this->hex())); }

        /**
        * Gets an exception from the statuscode
        *
        * @return Exception
        */
        public function exception() { return new Exception($this->error(), $this->code()); }

        /**
        * @param integer $code
        * @param string $name
        * @param string $readable
        */
        public function __construct($code, $name, $readable='') {
            $this->_code = $code;
            $this->_name = $name;
            $this->_readableName = $readable;
        }
    }