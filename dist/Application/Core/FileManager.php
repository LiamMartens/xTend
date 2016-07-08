<?php
    namespace xTend\Core;
    class FileManager
    {
        private $_files;
        public function __construct() {
            $this->_files=[];
        }

        /**
        * Includes a file if it hasn't been included yet
        *
        * @param string $fullPath
        */
        public function includeFile($fullPath) {
            $fullPath=realpath($fullPath);
            if(array_search($fullPath, $this->_files)===false) {
                include($fullPath);
                $this->_files[]=$fullPath;
            }
        }

        /**
        * Includes mulitple files
        *
        * @param array $filePaths
        */
        public function includeFiles($filePaths) {
            foreach ($filePaths as $path) {
                $this->includeFile($path);
            }
        }
    }
