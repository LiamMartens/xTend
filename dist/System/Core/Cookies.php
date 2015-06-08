<?php
	namespace xTend
	{
		class Cookies
		{
			private static $_enc_key;

			public static function PostConfiguration() {
				//generate new encryption key
				self::$_enc_key = hash("sha512", $_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
			}

			public static function Set($key,$value,$time=false,$domain='/') {
				if($time===false){$time=time()+3600*24;}
				setcookie(sha1($key),\Crypto::Encrypt($value,self::$_enc_key),$time,$domain);
			}

			public static function Get($key,$default=false) {
				if(isset($_COOKIE[sha1($key)])) {
					return \Crypto::Decrypt($_COOKIE[sha1($key)],self::$_enc_key);
				}
				return $default;
			}

			public static function Remove($key,$domain='/') {
				if(isset($_COOKIE[sha1($key)])) {
					self::Set($key,null,time()-1,$domain);
					unset($_COOKIE[sha1($key)]);
					return true;
				}
				return false;
			}
		}
	}