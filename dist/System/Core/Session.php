<?php
	namespace xTend
	{
		class Session
		{
			private static $_session_name = "secure_session_name";
			private static $_initiated = "secure_initiated_key";
			private static $_user_agent = "secure_user_agent_key_name";
			private static $_salt = "secure_salt";

			public static function SessionName($name) { self::$_session_name = $name; }
			public static function InitiatedKey($key) { self::$_initiated = $key; }
			public static function UserAgentKey($key) { self::$_user_agent = $key; }
			public static function Salt($salt) { self::$_salt = $salt; }

			public static function Destroy() {
				session_unset();
				session_destroy();
			}

			public static function Start() {
				//only allow http
				ini_set('session.cookie_httponly', true);
				//start session
				session_name(self::$_session_name);
				session_start();
				//generate key
				Sessions::GenerateKey();
				//check instantiated
				if(Sessions::Get(self::$_initiated)==0) {
					session_regenerate_id();
					Sessions::GenerateKey();
					Sessions::Set(self::$_initiated,1);
				}
				//check user agent
				if(Sessions::Get(self::$_user_agent)!==false) {
					if(Sessions::Get(self::$_user_agent) !== hash("sha256", $_SERVER['HTTP_USER_AGENT'].self::$_salt)) { self::Destroy(); URL::to(""); die(); }
				} else { Sessions::Set(self::$_user_agent,hash("sha256", $_SERVER['HTTP_USER_AGENT'].self::$_salt)); }
			}
		}
	}
