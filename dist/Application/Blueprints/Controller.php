<?php
    namespace Application\Blueprints;
    /**
    * The BaseController is the most basic type of controllor there is
    * it contains a constructor to include the current application
    */
    class Controller extends DataExtension {
        /** @var array Contains all models */
        protected $_models;
        /** @var Model Contains the first model */
        protected $_model;

        /**
        * Creates a new BaseController
        *
        * @param xTend\Core\App $app Current application
        */
        public function __construct($models) {
            $this->_models = $models;
            $keys=array_keys($this->_models);
            if(count($keys)>0) { $this->_model = &$this->_models[$keys[0]]; }
        }
    }