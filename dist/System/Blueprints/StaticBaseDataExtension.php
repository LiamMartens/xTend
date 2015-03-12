<?php
	namespace xTend
	{
		abstract class StaticBaseDataExtension
		{
			//For passing and saving data into controllers and views
			protected static $_Data = array();
			//Get Data
			public static function GetData($Key) {
				if(array_key_exists($Key,self::$_Data)) {
					return self::$_Data[$Key];
				}
				return false;
			}
			//Set Data
			public static function SetData($Key,$Value) {
				self::$_Data[$Key]=$Value;
				return true;
			}
			//Is in data?
			public static function InData($Key) {
				return array_key_exists($Key,self::$_Data);
			}
			//Return all data
			public static function AllData() {
				return self::$_Data;
			}
		}
	}