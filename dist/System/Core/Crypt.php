<?php
	namespace xTend
	{
		class Crypt
		{
			public static function Create($data,$key) {
				return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $data, MCRYPT_MODE_CBC, md5(md5($key))));
			}
			public static function Solve($data,$key) {
				return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($data), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
			}
		}
	}