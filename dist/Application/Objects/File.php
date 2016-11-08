<?php
    namespace Application\Objects\FileHandler;
    use Application\Core\App;
    use Application\Core\FileManager;
    use Application\Objects\DirectoryHandler\Directory;

    /**
    * The File class wraps a file
    */
    class File {
        /** @var string The path of the file */
        private $_path;

        /**
        * Cleans a path from starting and ending slashes + replaces backlashes with forward slashes
        *
        * @param string $path
        *
        * @return string $path
        */
        private function clean($path) { return trim(rtrim(str_replace('\\', '/', $path), '/')); }

        /**
        * Sets the path of the File object
        *
        * @param string $path
        */
        private function set($path) { $this->_path = $this->clean($path); }

        /**
        * @param string $path
        */
        public function __construct($path) {
            //always use setPath in order to automatically clean the path
            //aka replace \ with / as this is better
            $this->set($path);
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
        * Returns an array of defined classes in the file
        *
        * @return array
        */
        public function classes() {
            // array for all classnames
            $classes = [];
            // get contents of the file
            $contents = $this->read();
            // tokenize it
            $tokens = token_get_all($contents);
            // for class and namespace building
            $current_ns = ''; $namespace_build=false; $skip_used=false;
            $current_class = ''; $class_build=false;
            // loop through tokens
            foreach($tokens as $token) {
                $end_build=false;
                if(!is_string($token)) {
                    if($token[0]===T_NAMESPACE) {
                        $current_ns='';
                        $namespace_build=true;
                        $skip_used=false;
                    }
                    if(($token[0]===T_CLASS)||($token[0]===T_INTERFACE)||($token[0]===T_TRAIT)) {
                        $current_class='';
                        $class_build=true;
                        $skip_used=false;
                    }
                    if(($token[0]===T_STRING)||($token[0]===T_NS_SEPARATOR)) {
                        if($namespace_build) {
                            $current_ns.=$token[1];
                        } elseif($class_build) {
                            $current_class.=$token[1];
                        }
                    }
                    if($token[0]===T_WHITESPACE) {
                        if($skip_used) {
                            $end_build=true;
                        } else { $skip_used=true; }
                    }
                } elseif(($token==';')||($token=='{')) {
                    $end_build=true;
                }
                // end build if necessary after ; { or whitespace
                if($end_build) {
                    if($class_build) {
                        $classes[]=$current_ns.$current_class;
                    }
                    if($namespace_build) {
                        $current_ns.='\\';
                    }
                    $namespace_build=false;
                    $class_build=false;
                }
            }
            return $classes;
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
            return new Directory(substr($this->_path, 0, $sl_pos));
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
            if((new File($dest))->parent()->create())
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
            if((new File($dest))->parent()->create())
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
        * Gets, sets or removes meta data
        * set value to null and unset to true to
        * remove a value from the meta file
        *
        * @param mixed $key
        * @param mixed $value
        *
        * @return mixed
        */
        public function meta($key, $value=null, $unset=false) {
            if($this->exists()) {
                $m_file=App::meta()->file(hash('sha256', $this->_path).'.meta');
                $meta=[]; if($m_file->exists()) { $meta=json_decode($m_file->read(), true); }
                if(($value===null)&&(isset($meta[$key]))) {
                    return $meta[$key];
                } elseif(($value===null)&&($unset===true)) {
                    unset($meta[$key]);
                } else { $meta[$key]=$value; }
                return $m_file->write(json_encode($meta));
            }
            return false;
        }

        /**
        * Includes the file
        */
        public function include() {
            if($this->exists()) {
                return FileManager::include($this->_path);
            }
            return false;
        }

        /**
        * Gets the extension of the file (excluding the .)
        *
        * @return string|boolean
        */
        public function extension() {
            $index = strrpos($this->_path, '.');
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