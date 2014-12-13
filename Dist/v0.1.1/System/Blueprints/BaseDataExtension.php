<?php
	abstract class BaseDataExtension
	{
		protected $_Data = array();
		public function GetData($key) {
			if(array_key_exists($key,$this->_Data)) {
				return $this->_Data[$key];
			}
			return false;
		}
		public function SetData($key,$value) {
			$this->_Data[$key]=$value;
			return true;
		}
		public function InData($key) {
			return array_key_exists($key,$this->_Data);
		}
		public function AllData() {
			return $this->_Data;
		}
	}
?>