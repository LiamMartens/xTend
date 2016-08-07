<?php
    namespace Application\Core;
    use Application\Objects\DirectoryHandler\Directory;

    /**
    * The DirectoryHandler handles getting directory entries
    * as Directory object from the Application or public directory
    */
    class DirectoryHandler {
        /**
        * Gets a directory from the application directory
        *
        * @param string $dirName
        *
        * @return Directory
        */
        public static function system($dirName) {
            $path=App::system();
            $dir_parts = explode('.', $dirName);
            //foreach loop is possible here
            $path.='/'.implode('/', $dir_parts);
            return new Directory($path);
        }

        /**
        * Gets a directory from the public directory
        *
        * @param string $dirName
        *
        * @return Directory
        */
        public static function public($dirName) {
            $path=App::public();
            $dir_parts = explode('.', $dirName);
            //foreach loop is possible here
            $path.='/'.implode('/', $dir_parts);
            return new Directory($path);
        }
    }