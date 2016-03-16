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
				$backups = $this->_app->getDirectoryHandler()->files($this->_app->getDirectoryHandler()->systemDirectory($this->_app->getBackupsDirectory())); sort($backups);
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
				$backups = $this->_app->getDirectoryHandler()->files($this->_app->getDirectoryHandler()->systemDirectory($this->_app->getBackupsDirectory()), true); sort($backups);
				$to_remove = count($backups) - $this->_app->getBackupLimit();
				if($to_remove>0) {
					for($i=0;$i<$to_remove;$i++) {
						$this->_app->getFileHandler()->remove($backups[$i]);
					}
				}
			}
		}
		public function create($force=false) {
			if((!$this->needsBackup())&&(!$force)) return false;
			$bak = new Archive($this->_app->getFileHandler()->systemFile($this->_app->getBackupsDirectory().".".time()."-".date("YmdHis").".zip"));
			$systemfiles = $this->_app->getDirectoryHandler()->recursiveFiles($this->_app->getSystemDirectory());
			$sysdir_len = strlen($this->_app->getSystemDirectory())+1;
			$sysdir_name = substr($this->_app->getSystemDirectory(), strrpos($this->_app->getSystemDirectory(), "/")+1);
			$bak_dir_pref = $this->_app->getDirectoryHandler()->systemDirectory($this->_app->getBackupsDirectory());
			foreach ($systemfiles as $sysfile) {
				$sysfile_relname = substr($sysfile, $sysdir_len);
				if(substr($sysfile_relname, 0, 8)!="$bak_dir_pref/") { $bak->addFile($sysfile, "$sysdir_name/$sysfile_relname"); }
			}
			$publicfiles = $this->_app->getDirectoryHandler()->recursiveFiles($this->_app->getPublicDirectory());
			$pubdir_len = strlen($this->_app->getPublicDirectory())+1;
			$pubdir_name = substr($this->_app->getPublicDirectory(), strrpos($this->_app->getPublicDirectory(), "/")+1);
			foreach ($publicfiles as $pubfile) {
				$pubfile_relname = substr($pubfile, $pubdir_len);
				$bak->addFile($pubfile, "$pubdir_name/$pubfile_relname");
			}
			$bak->save();
			//clean backups here
			$this->cleanBackups();
		}
	}