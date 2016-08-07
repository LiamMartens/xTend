<?php
    namespace Application\Core;
    use Application\Objects\Archive;

    /**
    * The BackupManager handles taking backups
    * from the current application
    */
    class BackupManager {
        /**
        * Checks whether the application's backup interval has been exceeded
        *
        * @return boolean
        */
        private static function needs() {
            $interval=App::backupInterval();
            if($interval!==false) {
                $interval = strtotime($interval);
                $backups = App::backups()->files(); sort($backups);
                if(count($backups)>0) {
                    $last_backup = $backups[count($backups) - 1];
                    $last_backup_name = substr($last_backup, 0, strrpos($last_backup, '.'));
                    $last_backup_time = doubleval(substr($last_backup_name, 0, strpos($last_backup_name, '-')));
                    if($last_backup_time+$interval<=time()) return true;
                } else return true; //no back up was made yet
            }
            return false;
        }

        /**
        * Cleans backups when there are too many backup files (backuplimit exceed)
        */
        private static function clean() {
            $limit=App::backupsLimit();
            if($limit!==false) {
                $backups = App::backups()->files(); sort($backups);
                $to_remove = count($backups) - $limit;
                if($to_remove>0) {
                    $i=0; while($i<$to_remove) {
                        $backups[$i]->remove(); ++$i;
                    }
                }
            }
        }

        /**
        * Creates a backup if needed or forced
        *
        * @param boolean $force
        */
        public static function create($force=false) {
            if((!self::needs())&&(!$force)) return false;
            $bakdir=App::backups();
            $bak = new Archive($bakdir->file(time().'-'.date('YmdHis').'.zip'));
            //add system files
            $sysdir_len = strlen(App::system()->parent());
            $files = App::system()->files(true);
            foreach($files as $file) {
                if($file->parent()!=$bakdir) {
                    $bak->addFile($file, substr($file, $sysdir_len+1));
                }
            }
            //add public
            $pubdir_len = strlen(App::public()->parent());
            $files = App::public()->files(true);
            foreach($files as $file) {
                $bak->addFile($file, substr($file, $pubdir_len+1));
            }
            $bak->save();
            self::clean();
        }
    }
