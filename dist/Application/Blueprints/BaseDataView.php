<?php
	namespace xTend\Blueprints;
	class BaseDataView extends BaseView
	{
		/**
			NOTICE: The code in here is the same as in BaseDataExtension but PHP sadly doesn't support muti inheritance,
					So I had to duplicate the code, though I still added the BaseDataExtension code for those who want custom
					Controllers with the same data functionality (also, the UrlHandler extends BaseDataExtension)
		**/
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
