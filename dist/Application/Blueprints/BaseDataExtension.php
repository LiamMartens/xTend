<?php
    namespace xTend\Blueprints;
    class BaseDataExtension
    {
        protected $_data=[];
        /*
        * Sets a data entry on the BaseDataExtension
        *
        * @param mixed $key
        * @param mixed $value
        */
        public function setData($key,$value) {
            $this->_data[$key]=$value;
        }
        /*
        * Gets a data entry from the BaseDataExtension
        *
        * @param mixed $key
        * @param mixed $default
        *
        * @return mixed
        */
        public function getData($key, $default=false) {
            if(array_key_exists($key, $this->_data))
                return $this->_data[$key];
            return $default;
        }
        /*
        * Checks whether a certain data entry exists in the BaseDataExtension
        *
        * @param mixed $key
        *
        * @return boolean
        */
        public function inData($key) {
            return array_key_exists($key, $this->_data);
        }
        /*
        * Returns all data entries
        *
        * @return array
        */
        public function getAllData() {
            return $this->_data;
        }
        /*
        * Clears all data entries from the BaseDataExtension
        *
        * @return xTend\Blueprints\BaseDataExtension
        */
        public function clearData() {
            $this->_data = [];
            return $this;
        }
        /*
        * Sets a data entry on the BaseDataExtension
        *
        * @param mixed $key
        * @param mixed $value
        */
        public function __set($name, $value) {
            if($name=='_data') {
                $this->_data = $value;
            } else { $this->setData($name, $value); }
        }
        /*
        * Gets a data entry from the BaseDataExtension
        *
        * @param mixed $key
        * @param mixed $default
        *
        * @return mixed
        */
        public function __get($name) {
            if($this->inData($name))
                return $this->getData($name);
            return false;
        }
    }
