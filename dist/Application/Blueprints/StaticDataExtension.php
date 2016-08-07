<?php
    namespace Application\Blueprints;
    /**
    * The BaseDataExtension allows for data setting
    * and getting
    */
    class StaticDataExtension {
        /** @var array the dataset of the class */
        protected static $_data=[];
        /**
        * Sets a data entry on the BaseDataExtension
        *
        * @param mixed $key
        * @param mixed $value
        */
        public static function set($key,$value) {
            self::$_data[$key]=$value;
        }
        /**
        * Gets a data entry from the BaseDataExtension
        *
        * @param mixed $key
        * @param mixed $default
        *
        * @return mixed
        */
        public static function get($key, $default=false) {
            if(isset(self::$_data[$key]))
                return self::$_data[$key];
            return $default;
        }
        /**
        * Checks whether a certain data entry exists in the BaseDataExtension
        *
        * @param mixed $key
        *
        * @return boolean
        */
        public static function in($key) {
            return isset(self::$_data[$key]);
        }
        /**
        * Returns all data entries
        *
        * @return array
        */
        public static function all() {
            return self::$_data;
        }
        /**
        * Clears all data entries from the BaseDataExtension
        */
        public static function clear() {
            self::$_data = [];
        }
    }
