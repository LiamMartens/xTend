<?php
	namespace xTend
	{
		class Session
		{
			public static function Start() {
				$sname = session_name();
				if(!isset($_COOKIE["sname"])){setcookie('sname',sha1($_SERVER['REMOTE_ADDR']).hash("sha512", microtime()),time()+3600*24,'/');}
				elseif(strpos($_COOKIE["sname"],sha1($_SERVER['REMOTE_ADDR']))===0){setcookie('sname',$_COOKIE["sname"],time()+3600*24,'/');}
				else{setcookie('sname',sha1($_SERVER['REMOTE_ADDR']).hash("sha512", microtime()),time()+3600*24,'/');}
				session_name($_COOKIE["sname"]);
				session_start();
				return false;
			}
		}
	}