<?php
    namespace xTend\Blueprints;
    class BaseView
    {
        protected $_app;
        public function __construct($app) {
            $this->_app = $app;
        }
    }
