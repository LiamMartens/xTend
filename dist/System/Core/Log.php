<?php
	namespace xTend
	{
		//Using
		use \DateTime as  DateTime;

		class Log
		{
			private static function CleanLogs() {
				$files = Dir::Files(Dir::System("Logs"));
				sort($files);
				while(count($files) > Config::LogLimit) {
					File::Remove(File::System("Logs.".$files[0]));
					unset($files[0]);
					$files = array_values($files);
				}
			}
			public static function PHPError($Message) {
				$DateTime = new DateTime();
				//Write to PHP Error Log file
				File::Append(
					File::System("Logs.PHPError-".$DateTime->format("d-m-Y").".log"),
					$DateTime->format("H:i:s")."    (".App::RequestUrl()."):    ".$Message."\r\n"
				);
				//clean logs
				self::CleanLogs();
			}
			public static function PHPException($Message) {
				$DateTime = new DateTime();
				//Write to PHP Error Log file
				File::Append(
					File::System("Logs.PHPException-".$DateTime->format("d-m-Y").".log"),
					$DateTime->format("H:i:s")."    (".App::RequestUrl()."):    ".$Message."\r\n"
				);
				//clean logs
				self::CleanLogs();
			}
			public static function AppError($Message) {
				$DateTime = new DateTime();
				//Write to PHP Error Log file
				File::Append(
					File::System("Logs.AppError-".$DateTime->format("d-m-Y").".log"),
					$DateTime->format("H:i:s")."    (".App::RequestUrl()."):    ".$Message."\r\n"
				);
				//clean logs
				self::CleanLogs();
			}
		}
	}