<?php
    namespace xTend\Blueprints;
    class BaseDataView extends BaseView
    {
        /**
            NOTICE: The code in here is the same as in BaseDataExtension but PHP sadly doesn't support muti inheritance,
                    So I had to duplicate the code, though I still added the BaseDataExtension code for those who want custom
                    Controllers with the same data functionality (also, the UrlHandler extends BaseDataExtension)
        **/
        protected $_data=[];
        /*
        * Sets a data entry on the BaseDataView
        *
        * @param mixed $key
        * @param mixed $value
        */
        public function setData($key,$value) {
            $this->_data[$key]=$value;
        }
        /*
        * Gets a data entry from the BaseDataView
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
        * Checks whether a certain data entry exists in the BaseDataView
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
        * Clears all data entries from the BaseDataView
        *
        * @return xTend\Blueprints\BaseDataView
        */
        public function clearData() {
            $this->_data = [];
            return $this;
        }
        /*
        * Sets a data entry on the BaseDataView
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
        * Gets a data entry from the BaseDataView
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
