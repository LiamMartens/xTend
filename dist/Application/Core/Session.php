<?php
	namespace xTend\Core;
	/**
		The session class is also static as there should only be 1
		interaction class active on a single domain / page otherwise you'll create
		inaccessible data -> make sure to check when running multiple xTend App instances
		that there aren't multiple keys otherwise you'll have encrypted session data without
		the possibility to decrypt it
	**/
	use \Defuse\Crypto\Crypto as Crypto;
	class Session
	{
		private static $_enckey;
		public static function generate($key) {
			self::$_enckey = sha1(session_name().session_id().$key);
		}

		public static function remove($key) {
			if(isset($_SESSION[sha1($key)]))
				unset($_SESSION[sha1($key)]);
		}

		public static function set($key, $value) {
			try {
				$_SESSION[sha1($key)] = Crypto::encrypt($value, self::$_enckey);
			} catch(\Exception $e) { self::remove($key); }
		}

		public static function get($key, $default=false) {
			if(isset($_SESSION[sha1($key)])) {
				try {
					return Crypto::decrypt($_SESSION[sha1($key)], self::$_enckey);
				} catch (\Exception $e) { self::remove($key); }
			}
			return $default;
		}
	}
