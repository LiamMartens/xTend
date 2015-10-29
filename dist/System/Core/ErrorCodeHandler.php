<?php
	namespace xTend
	{
		use \Exception as Exception;

		class ErrorCode
		{
			protected $_code;
			protected $_name;
			protected $_humanName;

			public function __construct($code, $name, $human='') {
				$this->_code=$code;
				$this->_name=$name;
				$this->_humanName=$human;
			}
			public function getCode() {
				return $this->_code;
			}
			public function getHexCode() {
				$hex=strval(dechex($this->_code));
				return ("0x".str_pad($hex, 4, "0", STR_PAD_LEFT));
			}
			public function getName() {
				return $this->_name;
			}
			public function getHumanName() {
				return $this->_humanName;
			}
			public function getError() {
				return $this->getHexCode()." ".$this->getName()." ".$this->getHumanName();
			}
			public function isError($key) {
				if(($key===$this->_code) || ($key===$this->_name)) {
					return true;
				}
				return false;
			}
			public function getException() {
				return new Exception($this->getError(), $this->getCode());
			}
		}

		class ErrorCodeHandler
		{
			protected static $_errorCodes=[];

			public static function PreConfiguration() {
				self::RegisterErrorCode(0x0000, "errorcodehandler:invalid-code", "Error in class ErrorCodeHandler: Trying to register invalid error code");
				self::RegisterErrorCode(0x0001, "errorcodehandler:invalid-name", "Error in class ErrorCodeHandler: Trying to register empty name");
				self::RegisterErrorCode(0x0194, "http:404-not-found", "HTTP error 404: File Not Found");
			}
			public static function FindError($key) {
				if(is_numeric($key)&&(array_key_exists($key, self::$_errorCodes))) {
					//provided key is numeric -> find it in the directory itself
					return self::$_errorCodes[$key];
				} elseif(is_string($key)) {
					//check every errorCode
					foreach (self::$_errorCodes as $c) {
						if($c->isError($key)) { return $c; }
					}
				}
			}
			public static function RegisterErrorCode($code, $name, $human='') {
				if(!is_numeric($code)) { throw new Exception(self::FindError(0x0000)->getError(), 0x0000); }
				if(strlen($name)==0) { throw new Exception(self::FindError(0x0001)->getError(), 0x0001); }
				self::$_errorCodes[$code]=new ErrorCode($code,$name,$human);
			}
		}
	}