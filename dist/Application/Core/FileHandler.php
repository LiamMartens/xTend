<?php
    namespace Application\Core;
    use Application\Objects\FileHandler\File;

    /**
    * The FileHandler handles
    * getting files as File objects
    * from application or public
    * directory
    */
    class FileHandler {
        /**
        * Gets a file from the application directory
        *
        * @param string $fileName
        * @param integer $ext_count
        *
        * @return File
        */
        public static function system($fileName, $ext_count = 1) {
            $path=App::system();
            $file_parts = explode('.', $fileName);
            //for loop here since we need to exclude the last part of the array -> extension
            $file_parts_count = count($file_parts)-$ext_count;
            $path.='/'.implode('/', array_slice($file_parts, 0, $file_parts_count));
            //add extension part
            if($ext_count>0) {
                $path.='.'.implode('.', array_slice($file_parts, $file_parts_count));
            }
            return new File($path);
        }

        /**
        * Gets a file from the public directory
        *
        * @param string $fileName
        * @param integer $ext_count
        *
        * @return File
        */
        public static function public($fileName, $ext_count = 1) {
            $path=App::public();
            $file_parts = explode('.', $fileName);
            //for loop here since we need to exclude the last part of the array -> extension
            $file_parts_count = count($file_parts)-$ext_count;
            $path.='/'.implode('/', array_slice($file_parts, 0, $file_parts_count));
            //add extension part
            if($ext_count>0) {
                $path.='.'.implode('.', array_slice($file_parts, $file_parts_count));
            }
            return new File($path);
        }
    }