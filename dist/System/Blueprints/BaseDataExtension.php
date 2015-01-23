<?php
	namespace xTend
	{
		abstract class BaseDataExtension
		{
			//For passing and saving data into controllers and views
			protected $_Data = array();
			//Get Data
			public function GetData($Key) {
				if(array_key_exists($Key,$this->_Data)) {
					return $this->_Data[$Key];
				}
				return false;
			}
			//Set Data
			public function SetData($Key,$Value) {
				$this->_Data[$Key]=$Value;
				return true;
			}
			//Is in data?
			public function InData($Key) {
				return array_key_exists($Key,$this->_Data);
			}
			//Return all data
			public function AllData() {
				return $this->_Data;
			}
		}
	}
?>