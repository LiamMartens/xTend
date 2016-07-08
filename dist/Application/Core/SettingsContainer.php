<?php
    namespace xTend\Core;
    /**
    * The SettingsContainer handles
    * application values
    */
    class SettingsContainer
    {
        /** @var array Contains the setting values */
        private $_settings;
        public function __construct() {
            $this->_settings = [];
        }

        /**
        * Sets a settings entry
        *
        * @param mixed $key
        * @param mixed $value
        */
        public function set($key, $value) {
            $this->_settings[$key] = $value;
        }

        /**
        * Returns a settings entry
        *
        * @param mixed $key
        * @param mixed $default
        *
        * @return mixed
        */
        public function get($key, $default = false) {
            if(array_key_exists($key, $this->_settings))
                return $this->_settings[$key];
            return $default;
        }
    }
