<?php
	namespace xTend
	{
		class Session
		{
			public static function Name() {
				return session_name();
			}

			public static function Destroy() {
				if(isset($_COOKIE["sname"])&&(self::Name()==$_COOKIE["sname"])) {
					session_destroy($_COOKIE["sname"]);
				}
				setcookie("sname", null, time()-1, '/');
				unset($_COOKIE["sname"]);
				return true;
			}

			public static function Start() {
				$sname=sha1($_SERVER['REMOTE_ADDR']).hash("sha512", microtime());
				if(!isset($_COOKIE["sname"])){setcookie('sname',$sname,time()+3600,'/');}
				elseif(strpos($_COOKIE["sname"],sha1($_SERVER['REMOTE_ADDR']))!==0){setcookie('sname',$sname,time()+3600,'/');}
				else{setcookie('sname',$_COOKIE["sname"],time()+3600,'/');$sname=$_COOKIE["sname"];}
				session_name($sname);
				session_start();
				return true;
			}
		}
	}