<?php
	namespace xTend
	{
		//Using
		use \DateTime as  DateTime;

		class Log
		{
			public static function PHPError($Message) {
				$DateTime = new DateTime();
				//Write to PHP Error Log file
				File::Append(
					File::System("Logs.PHPError-".$DateTime->format("d-m-Y").".log"),
					$DateTime->format("H:i:s")."    (".App::RequestUrl()."):    ".$Message."\r\n"
				);
			}
			public static function PHPException($Message) {
				$DateTime = new DateTime();
				//Write to PHP Error Log file
				File::Append(
					File::System("Logs.PHPException-".$DateTime->format("d-m-Y").".log"),
					$DateTime->format("H:i:s")."    (".App::RequestUrl()."):    ".$Message."\r\n"
				);
			}
			public static function AppError($Error, $Message = "") {
				$DateTime = new DateTime();
				//Write to PHP Error Log file
				File::Append(
					File::System("Logs.AppError-".$DateTime->format("d-m-Y").".log"),
					$DateTime->format("H:i:s")."    (".App::RequestUrl()."):    $Error => $Message \r\n"
				);
			}
		}
	}