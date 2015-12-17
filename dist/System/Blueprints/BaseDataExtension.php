<?php
	namespace xTend
	{
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
			public function __get($name) {
				if($this->inData($name))
					return $this->getData($name);
				return false;
			}
		}
	}