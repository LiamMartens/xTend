<?php
	namespace xTend\Blueprints;
	class BaseModel
	{
		protected $_app;
		public function __construct($app) {
			$this->_app = $app;
		}
	}