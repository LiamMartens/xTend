<?php
	namespace xTend
	{
		class Sessions
		{
			private static $_enc_key;

			public static function GenerateKey() {
				$_key = sha1(session_name().session_id());
				self::$_enc_key = substr($_key, 5, 8).substr($_key, 25, 8);
			}

			public static function Set($key,$value) {
				$_SESSION[sha1($key)] = \Defuse\Crypto\Crypto::Encrypt($value,self::$_enc_key);
			}

			public static function Get($key,$default = false) {
				if(isset($_SESSION[sha1($key)])) {
					return \Defuse\Crypto\Crypto::Decrypt($_SESSION[sha1($key)],self::$_enc_key);
				}
				return $default;
			}

			public static function Remove($key) {
				if(isset($_SESSION[sha1($key)])) {
					unset($_SESSION[sha1($key)]);
					return true;
				}
				return false;
			}
		}
	}
