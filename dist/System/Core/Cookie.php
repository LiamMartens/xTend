<?php
	namespace xTend\Core;
	/**
		Same rules as the Session class apply here
		There should only be one cookie class as the key on the current machine will be the
		same anyways
	**/
	use \Defuse\Crypto\Crypto as Crypto;
	class Cookie
	{
		private static $_enckey;
		public static function generate() {
			self::$_enckey = sha1($_SERVER["HTTP_USER_AGENT"].$_SERVER["REMOTE_ADDR"]);
		}

		public function set($key, $value, $time=false, $domain='/') {
			if($time===false) $time=time()+3600*24; //one day
			try {
				setcookie(sha1($key), Crypto::encrypt($value, self::$_enckey));
			} catch(\Exception $e) { self::remove($key, $domain); }
		}

		public static function get($key, $default=false, $domain='/') {
			if(isset($_COOKIE[sha1($key)])) {
				try {
					return Crypto::decrypt($_COOKIE[sha1($key)], self::$_enckey);
				} catch(\Exception $e) { self::remove($key, $domain); }
			}
			return $default;
		}

		public static function remove($key, $domain='/') {
			if(isset($_COOKIE[sha1($key)])) {
				self::set($key,null,time()-1,$domain);
				unset($_COOKIE[sha1($key)]);
			}
		}
	}