<?php
    namespace xTend\Core;
    class Archive
    {
        private $_zip;
        private $_destination;

        /**
        * @param string $dest
        * @param boolean $read
        */
        public function __construct($dest, $read = false) {
            $this->_destination = $dest;
            $this->_zip = new \ZipArchive;
            if($read) {
                $this->_zip->open($this->_destination);
            } else { $this->_zip->open($this->_destination, \ZipArchive::CREATE); }
        }

        /**
        * adds a file to the ZipArchive
        *
        * @param string $filePath
        * @param string|boolean $zipPath
        */
        public function addFile($filePath, $zipPath = false) {
            if($zipPath===false) {
                $this->_zip->addFile($filePath);
            } else { $this->_zip->addFile($filePath, $zipPath); }
        }

        /**
        * Adds a directory to the ZipArchive
        *
        * @param string $dirPath
        */
        public function addDirectory($dirPath) {
            $this->_zip->addEmptyDir($dirPath);
        }

        /**
        * Deletes a file from the ZipArchive
        *
        * @param string $name
        */
        public function delete($name) {
            $this->_zip->deleteName($name);
        }

        /**
        * Extracts a ZipArchive
        *
        * @param string $dest
        */
        public function extract($dest) {
            $res = $this->_zip->extractTo($dest);
            $this->_zip->close();
            return $res;
        }

        /**
        * Closes a ZipArchive
        */
        public function save() {
            $res = $this->_zip->close();
        }
    }
