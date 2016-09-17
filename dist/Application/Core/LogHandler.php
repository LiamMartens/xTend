<?php
    namespace Application\Core;
    use \DateTime;


    /**
    * The LogHandler handles writing and clearing logs
    */
    class LogHandler {
        public static function clear() {
            $files = App::logs()->files();
            foreach ($files as $file) { $file->remove(); }
        }
        public static function clean() {
            $files = App::logs()->files(); sort($files);
            $files_to_remove = count($files) - App::logLimit();
            if($files_to_remove>0) {
                $i=0; while($i<$files_to_remove) {
                    $files[$i]->remove(); ++$i;
                }
            }
        }


        /**
        * Writes a log
        *
        * @param StatusCode $err
        * @param string $additional
        */
        public static function write($err, $additional = '') {
            $dt = new DateTime();
            $file=App::logs()->file('log_'.$dt->format('Y-m-d').'.log');
            $log=$dt->format('H:i:s')."\t".$err->status()."\t$additional\r\n";
            if($file->exists()) {
                $file->append($log);
            } else { $file->write($log); }
            self::clean();
        }
    }