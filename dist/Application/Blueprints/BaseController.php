<?php
    namespace xTend\Blueprints;
    /**
    * The BaseController is the most basic type of controllor there is
    * it contains a constructor to include the current application
    */
    class BaseController
    {
        /** @var xTend\Core\App|null Contains the current application */
        protected $_app;
        /**
        * Creates a new BaseController
        *
        * @param xTend\Core\App $app Current application
        */
        public function __construct($app) {
            $this->_app = $app;
        }
    }
