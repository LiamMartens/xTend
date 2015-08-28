<?php
	namespace xTend
	{
		class Variables
		{
			private static $_vars = array();
			
			public static function Set($key, $value) {
				self::$_vars[$key] = $value;
			}
			public static function Get($key, $default = false) {
				if(array_key_exists($key, self::$_vars)) {
					return self::$_vars[$key];
				}
				return $default;
			}
		}
	}