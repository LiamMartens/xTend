<?php
    namespace xTend\Blueprints;
    /*
    * The BaseView the most basic view to create
    */
    class BaseView
    {
        protected $_app;
        /**
        * Constructs a BaseView
        *
        * @param xTend\Blueprints\BaseView $app
        */
        public function __construct($app) {
            $this->_app = $app;
        }
    }
