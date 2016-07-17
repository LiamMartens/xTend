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
        /** @var array Contains all models */
        protected $_models;
        /** @var Model Contains the first model */
        protected $_model;

        /**
        * Creates a new BaseController
        *
        * @param xTend\Core\App $app Current application
        */
        public function __construct($app, $models) {
            $this->_app = $app;
            $this->_models = $models;
            $keys=array_keys($this->_models);
            if(count($keys)>0) { $this->_model = &$this->_models[$keys[0]]; }
        }
    }