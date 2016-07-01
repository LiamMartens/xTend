<?php
	namespace xTend\Blueprints;
	class BaseDataExtension
	{
		protected $_data=[];
		public function setData($key,$value) {
			$this->_data[$key]=$value;
		}
		public function getData($key, $default=false) {
			if(array_key_exists($key, $this->_data))
				return $this->_data[$key];
			return $default;
		}
		public function inData($key) {
			return array_key_exists($key, $this->_data);
		}
		public function getAllData() {
			return $this->_data;
		}
		public function clearData() {
			$this->_data = [];
			return $this;
		}
		public function __set($name, $value) {
			if($name=='_data') {
				$this->_data = $value;
			} else { $this->setData($name, $value); }
		}
		public function __get($name) {
			if($this->inData($name))
				return $this->getData($name);
			return false;
		}
	}
