<?php
	namespace xTend
	{
		use \Exception as Exception;
		class ErrorCode
		{
			protected $_code;
			protected $_name;
			protected $_readableName;

			public function getCode() { return $this->_code; }
			public function getHexCode() { return ("0x".str_pad(strval(dechex($this->getCode())), 4, "0", STR_PAD_LEFT)); }
			public function getName() { return $this->_name; }
			public function getReadableName() { return $this->_readableName; }
			public function getError() { return "(".$this->getHexCode()." ".$this->getName().") ".$this->getReadableName(); }
			public function isError($key) { return (($key===$this->getCode())||($key===$this->getName())||($key==$this->getHexCode())); }
			public function getException() { return new Exception($this->getError(), $this->getCode()); }

			public function __construct($code, $name, $readable="") {
				$this->_code = $code;
				$this->_name = $name;
				$this->_readableName = $readable;
			}
		}
		class ErrorCodeHandler
		{
			protected $_errorCodes;

			public function getErrorCodes() {
				return $this->_errorCodes;
			}

			public function findError($key) {
				if(is_numeric($key)&&(array_key_exists($key, $this->_errorCodes))) {
					//provided key is numeric -> found it in error code directory itself
					return $this->_errorCodes[$key];
				} elseif(is_string($key)) {
					foreach ($this->_errorCodes as $c) {
						if($c->isError($key)) return $c;
					}
				}
 			}

			public function registerErrorCode($code, $name, $readable = "") {
				if(!is_numeric($code)) { throw $this->findError(0x0000)->getException(); }
				if(strlen($name)==0) { throw $this->findError(0x0001)->getException(); }
				$this->_errorCodes[$code]=new ErrorCode($code,$name,$readable);
			}

			public function __construct() {
				//initialize default error codes
				$this->registerErrorCode(0x0000, "errorcodehandler:invalid-code","Error in ErrorCodeHandler: Trying to register invalid error code");
				$this->registerErrorCode(0x0001, "errorcodehandler:invalid-name","Error in ErrorCodeHandler: Trying to register invalid error name");
				$this->registerErrorCode(0x0194, "http:404", "HTTP 404: Page not found");
			}
		}
	}