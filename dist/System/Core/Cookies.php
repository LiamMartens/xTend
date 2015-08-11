<?php
	namespace xTend
	{
		class Cookies
		{
			public static $_enc_key;

			public static function PreConfiguration() {
				//generate new encryption key
				$_key = sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
				self::$_enc_key = substr($_key, 5, 8).substr($_key, 25, 8);
			}

			public static function Set($key,$value,$time=false,$domain='/') {
				if($time===false){$time=time()+3600*24;}
				try {
					setcookie(sha1($key),\Defuse\Crypto\Crypto::encrypt($value,self::$_enc_key),$time,$domain);
				} catch (\Exception $e) { self::Remove($key); }
			}

			public static function Get($key,$default=false) {
				if(isset($_COOKIE[sha1($key)])) {
					try {
						return \Defuse\Crypto\Crypto::decrypt($_COOKIE[sha1($key)],self::$_enc_key);
					} catch (\Exception $e) { self::Remove($key); }
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