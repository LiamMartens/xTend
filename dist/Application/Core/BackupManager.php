<?php
    namespace xTend\Core;
    /**
    * The BackupManager handles taking backups
    * from the current application
    */
    class BackupManager
    {
        /** @var xTend\Core\App Contains the current application */
        private $_app;
        public function __construct($app) {
            $this->_app = $app;
        }

        /**
        * Checks whether the application's backup interval has been exceeded
        *
        * @return boolean
        */
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

        /**
        * Cleans backups when there are too many backup files (backuplimit exceed)
        */
        private function cleanBackups() {
            if($this->_app->getBackupLimit()!==false) {
                $backups = $this->_app->getBackupsDirectory()->files(); sort($backups);
                $to_remove = count($backups) - $this->_app->getBackupLimit();
                if($to_remove>0) {
                    $i=0; while($i<$to_remove) {
                        $backups[$i]->remove(); $i++;
                    }
                }
            }
        }

        /**
        * Creates a backup if needed or forced
        *
        * @param boolean $force
        */
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
