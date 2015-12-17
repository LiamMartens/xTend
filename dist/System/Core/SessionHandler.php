<?php
	namespace xTend
	{
		class SessionHandler
		{
			/**
				The SessionHandler is solely static since there can only be one session
				at a time on a single domain
			**/
			private static $_sessionName = "secure_session_name";
			private static $_initiated = "secure_initiated_key";
			private static $_userAgent = "secre_user_agent_key";
			private static $_salt = "secure_salt";
			//no getters as those are not needed
			public static function setSessionName($name) { self::$_sessionName = $name; }
			public static function setInitiatedKey($key) { self::$_initiated = $key; }
			public static function setUserAgentKey($key) { self::$_userAgent = $key; }
			public static function setSalt($salt) { self::$_salt = $salt; }

			public static function destroy() {
				session_unset(); session_destroy();
			}

			public static function start() {
				ini_set("session.cookie_httponly", true);
				//start session
				//set session name
				session_name(self::$_sessionName);
				session_start();
				//generate key
				Session::generate();
				//check initiated status
				if(intval(Session::get(self::$_initiated))==0) {
					//regen
					session_regenerate_id();
					Session::generate();
					Session::set(self::$_initiated, 1);
				}
				//check for corresponding user agent on same session
				if(Session::get(self::$_userAgent)!==false) {
					if(Session::get(self::$_userAgent)!=hash("sha512", $_SERVER["HTTP_USER_AGENT"].self::$_salt)) {
						//invalid user agent detected
						self::destroy(); die();
					}
				} else { Session::set(self::$_userAgent, hash("sha512", $_SERVER["HTTP_USER_AGENT"].self::$_salt)); }
			}
		}
	}