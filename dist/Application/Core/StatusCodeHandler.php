<?php
	namespace xTend\Core;
	use \Exception as Exception;
	class StatusCode
	{
		protected $_code;
		protected $_name;
		protected $_readableName;

		public function getCode() { return $this->_code; }
		public function getHexCode() { return ("0x".str_pad(strval(dechex($this->getCode())), 4, "0", STR_PAD_LEFT)); }
		public function getName() { return $this->_name; }
		public function getReadableName() { return $this->_readableName; }
		public function getStatus() { return "(".$this->getHexCode()." ".$this->getName().") ".$this->getReadableName(); }
		public function isStatus($key) { return (($key===$this->getCode())||($key===$this->getName())||($key==$this->getHexCode())); }
		public function getException() { return new Exception($this->getError(), $this->getCode()); }

		public function __construct($code, $name, $readable="") {
			$this->_code = $code;
			$this->_name = $name;
			$this->_readableName = $readable;
		}
	}
	class StatusCodeHandler
	{
		protected $_statusCodes;

		public function getStatusCodes() {
			return $this->_statusCodes;
		}

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
