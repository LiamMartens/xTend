<?php
	namespace xTend\Core;
	class BackupManager
	{
		/**
			Restore method is gone as it should not be done automatically
			You should consider yourself what to restore and what not as this is
			safer
		**/
		private $_app;
		public function __construct($app) {
			$this->_app = $app;
		}
		private function needsBackup() {
			if($this->_app->getBackupInterval()!==false) {
				$interval = strtotime($this->_app->getBackupInterval());
				$backups = $this->_app->getBackupsDirectory()->files(); sort($backups);
				if(count($backups)>0) {
					$last_backup = $backups[count($backups) - 1];
					$last_backup_name = substr($last_backup, 0, strrpos($last_backup, "."));
					$last_backup_time = doubleval(substr($last_backup_name, 0, strpos($last_backup_name, "-")));
					if($last_backup_time+$interval<=time()) return true;
				} else return true; //no back up was made yet
			}
			return false;
		}
		private function cleanBackups() {
			if($this->_app->getBackupLimit()!==false) {
				$backups = $this->_app->getBackupsDirectory()->files(); sort($backups);
				$to_remove = count($backups) - $this->_app->getBackupLimit();
				if($to_remove>0) {
					for($i=0;$i<$to_remove;$i++) {
						$backups[$i]->remove();
					}
				}
			}
		}
		public function create($force=false) {
			if((!$this->needsBackup())&&(!$force)) return false;
			$bak = new Archive($this->_app->getBackupsDirectory()->file(time()."-".date("YmdHis").".zip"));
			//add system files
			$bakdir = $this->_app->getBackupsDirectory();
			$sysdir_len = strlen($this->_app->getSystemDirectory()->parent());
			$files = $this->_app->getSystemDirectory()->files(true);
			foreach($files as $file) {
				if($file->parent()!=$bakdir) {
					$bak->addFile($file, substr($file, $sysdir_len+1));
				}
			}
			//add public
			$pubdir_len = strlen($this->_app->getPublicDirectory()->parent());
			$files = $this->_app->getPublicDirectory()->files(true);
			foreach($files as $file) {
				$bak->addFile($file, substr($file, $pubdir_len+1));
			}
			$bak->save();
			$this->cleanBackups();
		}
	}
