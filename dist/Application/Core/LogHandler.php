<?php
    namespace xTend\Core;
    use \DateTime as DateTime;
    class LogHandler
    {
        private $_app;
        /**
        * @param xTend\Core\App $app
        */
        public function __construct($app) {
            //store app reference for directives
            $this->_app = $app;
        }

        public function clearLogs() {
            $files = $this->_app->getLogsDirectory()->files();
            foreach ($files as $file) { $file->remove(); }
        }
        public function cleanLogs() {
            $files = $this->_app->getLogsDirectory()->files(); sort($files);
            $files_to_remove = count($files) - $this->_app->getLogLimit();
            if($files_to_remove>0) {
                for($i=0;$i<$files_to_remove;$i++) {
                    $files[$i]->remove();
                }
            }
        }

        /**
        * Writes a log
        *
        * @param StatusCode $err
        * @param string $additional
        */
        public function write($err, $additional = "") {
            $dt = new DateTime();
            $this->_app->getLogsDirectory()->file("log_".$dt->format("Y-m-d").".log")->append(
                $dt->format("H:i:s")."\t".$err->getStatus()."\t$additional\r\n"
            );
            $this->cleanLogs();
        }
    }
