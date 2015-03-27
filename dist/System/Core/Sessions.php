<?php
	namespace xTend
	{
		class Sessions
		{
			private static $_enc_key;

			public static function PostConfiguration() {
				//get a new key signature
				self::$_enc_key = hash("sha512", session_name().session_id());
			}

			public static function Set($key,$value) {
				$_SESSION[sha1($key)] = Crypt::Create($value,self::$_enc_key);
			}

			public static function Get($key,$default = false) {
				if(isset($_SESSION[sha1($key)])) {
					return Crypt::Solve($_SESSION[sha1($key)],self::$_enc_key);
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