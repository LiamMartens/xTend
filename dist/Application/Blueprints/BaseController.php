<?php
	namespace xTend\Blueprints;
	class BaseController
	{
		protected $_app;
		public function __construct($app) {
			$this->_app = $app;
		}
	}