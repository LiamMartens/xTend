<?php
	namespace xTend
	{
		class URL
		{
			private static $_Route = "";
			private static $_Request = "";
			private static $_Method = "ANY";
			private static $_Data = array();

			public static function To($url) {
				header("Location: " . Config::Url . "/$url");
			}

			public static function Route() {
				return self::$_Route;
			}

			public static function SetRoute($route) {
				self::$_Route = $route;
			}

			public static function Request() {
				return self::$_Request;
			}

			public static function SetRequest($request) {
				self::$_Request = $request;
			}

			public static function Method() {
				return self::$_Method;
			}

			public static function SetMethod($method) {
				self::$_Method = $method;
			}

			public static function GetParameter($name, $default) {
				if(array_key_exists($name, self::$_Data)) {
					return self::$_Data[$name];
				}
				return $default;
			}

			public static function SetParameter($name, $value) {
				self::$_Data[$name] = $value;
				return true;
			}

			public static function __callStatic($name, $args) {
				return self::GetParameter($name, (count($args)>0) ? $args[0] : false);
			}
		}
	}