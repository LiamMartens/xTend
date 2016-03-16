<?php
	namespace xTend;
	class SettingsContainer
	{
		private $_settings;
		public function __construct() {
			//initialize settings array
			$this->_settings = [];
		}
		public function set($key, $value) {
			$this->_settings[$key] = $value;
		}
		public function get($key, $default = false) {
			if(array_key_exists($key, $this->_settings))
				return $this->_settings[$key];
			return $default;
		}
	}