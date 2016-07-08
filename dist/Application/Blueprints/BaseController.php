<?php
    namespace xTend\Blueprints;
    class BaseController
    {
        protected $_app;
        /*
        * Creates a new BaseController
        *
        * @param xTend\Core\App $app
        */
        public function __construct($app) {
            $this->_app = $app;
        }
    }
