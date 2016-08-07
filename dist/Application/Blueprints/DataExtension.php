<?php
    namespace Application\Blueprints;
    /**
    * The BaseDataExtension allows for data setting
    * and getting
    */
    class DataExtension {
        /** @var array the dataset of the class */
        protected $_data=[];
        /**
        * Sets a data entry on the BaseDataExtension
        *
        * @param mixed $key
        * @param mixed $value
        */
        public function set($key,$value) {
            $this->_data[$key]=$value;
        }
        /**
        * Gets a data entry from the BaseDataExtension
        *
        * @param mixed $key
        * @param mixed $default
        *
        * @return mixed
        */
        public function get($key, $default=false) {
            if(isset($this->_data[$key]))
                return $this->_data[$key];
            return $default;
        }
        /**
        * Checks whether a certain data entry exists in the BaseDataExtension
        *
        * @param mixed $key
        *
        * @return boolean
        */
        public function in($key) {
            return isset($this->_data[$key]);
        }
        /**
        * Returns all data entries
        *
        * @return array
        */
        public function all() {
            return $this->_data;
        }
        /**
        * Clears all data entries from the BaseDataExtension
        *
        * @return xTend\Blueprints\BaseDataExtension
        */
        public function clear() {
            $this->_data = [];
        }
    }
