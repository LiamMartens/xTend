<?php
	namespace xTend
	{
		class Sessions
		{
			private static $_enc_key;

			public static function PostConfiguration() {
				self::$_enc_key = hash("sha512", session_name().session_id());
			}

			public static function Set($key,$value) {
				$_SESSION[sha1($key)] = \Crypto::Encrypt($value,self::$_enc_key);
			}

			public static function Get($key,$default = false) {
				if(isset($_SESSION[sha1($key)])) {
					return \Crypto::Decrypt($_SESSION[sha1($key)],self::$_enc_key);
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