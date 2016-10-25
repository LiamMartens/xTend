<?php
    namespace Application\Core;
    /**
    * The FileManager handles including files only once
    * in a faster manner than *_once
    */
    class FileManager {
        /**
        * Includes a file if it hasn't been included yet
        *
        * @param string $path
        *
        * @return boolean
        */
        public static function include($path) {
            if(!defined($path)) {
                $d=require($path);
                define($path, true);
                if(!empty($d)) {
                    return $d;
                }
                return true;
            }
            return false;
        }
    }