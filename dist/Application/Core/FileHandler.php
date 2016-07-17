<?php
    namespace xTend\Core\FileHandler {
        use xTend\Core\DirectoryHandler\Directory;

        /**
        * The File class wraps a file
        */
        class File {
            /** @var xTend\Core\App Current application */
            private $_app;
            /** @var string The path of the file */
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
            * Sets the path of the File object
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
            * Returns whether the file exists and is a actually a file
            *
            * @return boolean
            */
            public function exists() {
                return is_file($this->_path);
            }

            /**
            * Returns whether the file is writable
            *
            * @return boolean
            */
            public function writable() {
                return (is_writable($this->_path)&&$this->exists());
            }

            /**
            * Returns the name of the File
            *
            * @return string
            */
            public function name() {
                //returns the name of the file
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
            * Move the file
            *
            * @param string $dest
            *
            * @return boolean
            */
            public function move($dest) {
                //try creating parents first
                if((new File($this->_app, $dest))->parent()->create())
                    return rename($this->_path, $dest);
                return false;
            }

            /**
            * Copies a file
            *
            * @param string $dest
            *
            * @return boolean
            */
            public function copy($dest) {
                //try creating parents first
                if((new File($this->_app, $dest))->parent()->create())
                    return copy($this->_path, $dest);
                return false;
            }

            /**
            * Removes a file
            *
            * @return boolean
            */
            public function remove() {
                return unlink($this->_path);
            }

            /**
            * Reads the file
            *
            * @return mixed|boolean
            */
            public function read() {
                if($this->exists()) {
                    $handle = fopen($this->_path, 'r');
                    $content = (filesize($this->_path)>0) ? fread($handle, filesize($this->_path)) : false;
                    fclose($handle);
                    return $content;
                } return false;
            }

            /**
            * Writes to the file
            *
            * @param mixed $content
            *
            * @return boolean
            */
            public function write($content) {
                if($this->parent()->writable()) {
                    $handle = fopen($this->_path, 'w');
                    fwrite($handle, $content);
                    fclose($handle);
                    return true;
                } return false;
            }

            /**
            * Appends to the file
            *
            * @param mixed $content
            *
            * @return boolean
            */
            public function append($content) {
                if($this->writable()) {
                    $handle = fopen($this->_path, 'a');
                    fwrite($handle, $content);
                    fclose($handle);
                    return true;
                } return false;
            }

            /**
            * Gets file meta data
            *
            * @param mixed $key
            * @param mixed $default
            *
            * @return mixed
            */
            public function getMeta($key, $default = false) {
                if($this->exists()) {
                    $m_file = $this->_app->getMetaDirectory()->file(hash("sha256", $this->_path).".meta");
                    $meta=[]; if($m_file->exists()) $meta=json_decode($m_file->read(), true);
                    if(array_key_exists($key, $meta)) return $meta[$key];
                }
                return $default;
            }

            /**
            * Sets file meta data
            *
            * @param mixed $key
            * @param mixed $value
            *
            * @return mixed
            */
            public function setMeta($key, $value = null) {
                //set or remove meta
                if($this->exists()) {
                    $m_file = $this->_app->getMetaDirectory()->file(hash("sha256", $this->_path).".meta");
                    $meta=[]; if($m_file->exists()) $meta=json_decode($m_file->read(), true);
                    if($value!==null) {
                        $meta[$key]=$value;
                    } elseif(array_key_exists($key, $meta)) { unset($meta[$key]); }
                    return $m_file->write(json_encode($meta));
                }
                return false;
            }

            /**
            * Includes the file
            */
            public function include() {
                if($this->exists()) {
                    $this->_app->getFileManager()->includeFile($this->_path);
                }
            }

            /**
            * Gets the extension of the file (excluding the .)
            *
            * @return string|boolean
            */
            public function extension() {
                $index = strrpos($this->_path, ".");
                if($index!==false) {
                    return substr($this->_path, $index+1);
                }
                return false;
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
        * The FileHandler handles
        * getting files as File objects
        * from application or public
        * directory
        */
        class FileHandler
        {
            /** @var xTend\Core\App Current application */
            private $_app;

            /**
            * @param xTend\Core\App $app
            */
            public function __construct($app) {
                //store containing app reference so the FileHandler can use it's directives
                $this->_app = $app;
            }

            /**
            * Gets a file from the application directory
            *
            * @param string $fileName
            * @param integer $ext_count
            *
            * @return xTend\Core\FileHandler\File
            */
            public function system($fileName, $ext_count = 1) {
                $path=$this->_app->getSystemDirectory();
                $file_parts = explode(".", $fileName);
                //for loop here since we need to exclude the last part of the array -> extension
                $file_parts_count = count($file_parts)-$ext_count;
                $path.="/".implode("/", array_slice($file_parts, 0, $file_parts_count));
                //add extension part
                $path.=".".implode(".", array_slice($file_parts, $file_parts_count));
                return new FileHandler\File($this->_app, $path);
            }

            /**
            * Gets a file from the public directory
            *
            * @param string $fileName
            * @param integer $ext_count
            *
            * @return xTend\Core\FileHandler\File
            */
            public function public($fileName, $ext_count = 1) {
                $path=$this->_app->getPublicDirectory();
                $file_parts = explode(".", $fileName);
                //for loop here since we need to exclude the last part of the array -> extension
                $file_parts_count = count($file_parts)-$ext_count;
                $path.="/".implode("/", array_slice($file_parts, 0, $file_parts_count));
                //add extension part
                $path.=".".implode(".", array_slice($file_parts, $file_parts_count));
                return new FileHandler\File($this->_app, $path);
            }
        }
    }
