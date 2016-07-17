<?php
    namespace xTend\Core\DirectoryHandler {
        use \xTend\Core\FileHandler\File;
        /**
        * The Directory class wraps
        * a direcotry
        */
        class Directory {
            /** @var xTend\Core\App Current application */
            private $_app;

            /** @var string Contains the path of the Directory */
            private $_path;

            /**
            * Cleans a path from starting and ending slashes + replaces backlashes with forward slashes
            *
            * @param string $path
            *
            * @return string $path
            */
            private function cleanPath($path) { return trim(rtrim(str_replace('\\', '/', $path), '/')); }

            /**
            * Sets the path of the Directory object
            *
            * @param string $path
            */
            private function setPath($path) { $this->_path = $this->cleanPath($path); }

            /**
            * @param xTend\Core\App $app
            * @param string $path
            */
            public function __construct($app, $path) {
                $this->_app = $app;
                //always use setPath in order to automatically clean the path
                //aka replace \ with / as this is better
                $this->setPath($path);
            }

            /**
            * Returns whether the directory exists and is a directory
            *
            * @return boolean
            */
            public function exists() {
                return is_dir($this->_path);
            }

            /**
            * Returns whether the directory is writable
            *
            * @return boolean
            */
            public function writable() {
                return (is_writable($this->_path)&&$this->exists());
            }

            /**
            * Returns the name of the Directory
            *
            * @return string
            */
            public function name() {
                $sl_pos = strrpos($this->_path, '/');
                return substr($this->_path, $sl_pos + 1);
            }

            /**
            * Returns the parent directory
            *
            * @return xTend\Core\DirectoryHandler\Directory
            */
            public function parent() {
                $sl_pos = strrpos($this->_path, '/');
                return new Directory($this->_app, substr($this->_path, 0, $sl_pos));
            }

            /**
            * Scans the directory either recursively or not
            *
            * @param boolean $recursive
            *
            * @return array
            */
            public function scan($recursive = false) {
                $entries=[];
                //fetch files recursive or not
                if($recursive) {
                    foreach (new \RecursiveIteratorIterator(
                             new \RecursiveDirectoryIterator($this->_path,
                                \RecursiveDirectoryIterator::SKIP_DOTS),
                                \RecursiveIteratorIterator::SELF_FIRST) as $file) {
                        $entries[] = $file;
                    }
                } else { $entries = array_merge(glob($this->_path."/**"), glob($this->_path."/.[!.][!.]*")); }
                //run map on entries for class creation
                $array_map_result=[]; foreach($entries as $entry) {
                    if(is_file($entry)) {
                        $array_map_result[] = new File($this->_app, $entry);
                    } elseif(is_dir($entry)) { $array_map_result[] = new Directory($this->_app, $entry); }
                } return $array_map_result;
            }

            /**
            * Returns all files in the directory either recursively or not
            *
            * @param boolean $recursive
            *
            * @return array
            */
            public function files($recursive = false) {
                $entries = $this->scan($recursive);
                $array_filter_result=[]; foreach($entries as $entry) {
                    if(is_file($entry)) { $array_filter_result[]=$entry; }
                } return $array_filter_result;
            }

            /**
            * Returns all directories in the directory either recursively or not
            *
            * @param boolean $recursive
            *
            * @return array
            */
            public function directories($recursive = false) {
                $entries = $this->scan($recursive);
                $array_filter_result=[]; foreach($entries as $entry) {
                    if(is_dir($entry)) { $array_filter_result[]=$entry; }
                } return $array_filter_result;
            }

            /**
            * Creates the directory
            *
            * @return boolean
            */
            public function create() {
                return mkdir($this->_path, 0777, true);
            }

            /**
            * Moves a directory
            *
            * @return boolean
            */
            public function move($dest) {
                //try making directory first
                if((new Directory($dest))->create())
                    return rename($this->_path, $dest);
                return false;
            }

            /**
            * Copies a directory recursively
            *
            * @return boolean|null
            */
            public function copy($dest) {
                $directories = $this->directories();
                $files = $this->files();
                //make destination dir
                $dest = new Directory($dest);
                if(!$dest->create()||!$dest->writable()) {
                    //copy files to destination
                    foreach($files as $file) { $file->copy($dest."/".$file->name()); }
                    //repeat for directories
                    foreach($directories as $dir) { $dir->copy($dest."/".$dir->name()); }
                }
                return false;
            }

            /**
            * Removes a directory recursively
            *
            * @return boolean
            */
            public function remove() {
                $files = $this->files();
                $directories = $this->directories();
                //remove all files
                foreach($files as $file) { $file->remove(); }
                //remove all directories
                foreach($directories as $dir) { $dir->remove(); }
                //remove current directory
                return rmdir($this->_path);
            }

            /**
            * Gets a file from the directory
            *
            * @param string $name
            * @param integer $ext_count
            *
            * @return xTend\Core\FileHandler\File
            */
            public function file($name, $ext_count = 1) {
                $path=$this->_path;
                $file_parts = explode(".", $name);
                //for loop here since we need to exclude the last part of the array -> extension
                $file_parts_count = count($file_parts)-$ext_count;
                $path.="/".implode("/", array_slice($file_parts, 0, $file_parts_count));
                //add extension part
                $path.=".".implode(".", array_slice($file_parts, $file_parts_count));
                return new File($this->_app, $path);
            }

            /**
            * Gets a directory from the directory
            *
            * @param string name
            *
            * @return xTend\Core\DirectoryHandler\Directory
            */
            public function directory($name) {
                $path=$this->_path;
                $dir_parts = explode(".", $name);
                //foreach loop is possible here
                $path.="/".implode("/", $dir_parts);
                return new Directory($this->_app, $path);
            }

            /**
            * @return string
            */
            public function __toString() {
                return $this->_path;
            }
        }
    }
    namespace xTend\Core {
        /**
        * The DirectoryHandler handles getting directory entries
        * as Directory object from the Application or public directory
        */
        class DirectoryHandler
        {
            /** @var xTend\Core\App Current application */
            private $_app;

            /**
            * @param xTend\Core\App $app
            */
            public function __construct($app) {
                //store containing app reference so the DirectoryHandler can use it's directives
                $this->_app = $app;
            }

            /**
            * Gets a directory from the application directory
            *
            * @param string $dirName
            *
            * @return xTend\Core\DirectoryHandler\Directory
            */
            public function system($dirName) {
                $path=$this->_app->getSystemDirectory();
                $dir_parts = explode(".", $dirName);
                //foreach loop is possible here
                $path.="/".implode("/", $dir_parts);
                return new DirectoryHandler\Directory($this->_app, $path);
            }

            /**
            * Gets a directory from the public directory
            *
            * @param string $dirName
            *
            * @return xTend\Core\DirectoryHandler\Directory
            */
            public function public($dirName) {
                $path=$this->_app->getPublicDirectory();
                $dir_parts = explode(".", $dirName);
                //foreach loop is possible here
                $path.="/".implode("/", $dir_parts);
                return new DirectoryHandler\Directory($this->_app, $path);
            }
        }
    }
