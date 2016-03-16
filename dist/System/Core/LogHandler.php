<?php
	namespace xTend\Core;
	use \DateTime as DateTime;
	class LogHandler
	{
		private $_app;
		public function __construct($app) {
			//store app reference for directives
			$this->_app = $app;
		}
		public function clearLogs() {
			$files = $this->_app->getDirectoryHandler()->files($this->_app->getDirectoryHandler()->systemDirectory($this->_app->getLogsDirectory()), true);
			foreach ($files as $file) { $this->_app->getFileHandler()->remove($file); }
		}
		public function cleanLogs() {
			$files = $this->_app->getDirectoryHandler()->files($this->_app->getDirectoryHandler()->systemDirectory($this->_app->getLogsDirectory()), true); sort($files);
			$files_to_remove = count($files) - $this->_app->getLogLimit();
			if($files_to_remove>0) {
				for($i=0;$i<$files_to_remove;$i++) {
					$this->_app->getFileHandler()->remove($files[$i]);
				}
			}
		}
		public function write($err, $additional = "") {
			$dt = new DateTime();
			$this->_app->getFileHandler()->append(
				$this->_app->getFileHandler()->systemFile($this->_app->getLogsDirectory().".log_".$dt->format("Y-m-d").".log"),
				$dt->format("H:i:s")."\t".$err->getError()."\t$additional\r\n"
			);
			$this->cleanLogs();
		}
	}