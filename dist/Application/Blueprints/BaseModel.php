<?php
    namespace xTend\Blueprints;
    /**
    * The BaseModel is the most basic model
    */
    class BaseModel
    {
        protected $_app;
        /**
        * Constructs BaseModel
        *
        * @param xTend\Blueprints\BaseModel $app
        */
        public function __construct($app) {
            $this->_app = $app;
        }
    }
