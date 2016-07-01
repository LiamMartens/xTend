<?php
	namespace xTend\Core;
	class UrlHandler extends \xTend\Blueprints\BaseDataExtension
	{
		private $_route;
		public function getRoute() { return $this->_route; }
		public function setRoute($route) { $this->_route=$route; }

		private $_request;
		public function getRequest() { return $this->_request; }
		public function setRequest($request) { $this->_request=trim($request, "/"); }

		private $_method;
		public function getMethod() { return $this->_method; }
		public function setMethod($method) { $this->_method=$method; }

		private $_app;
		public function __construct($app) {
			$this->_app = $app;
		}
		public function navigate($request, $data = [], $inc_url = true) {
			//set temp data and time to live
			Session::set(session_id().'-xtend-data', json_encode($data));
			Session::set(session_id().'-xtend-ttl', 1);
			if(is_string($request))
				header("Location: ".(($inc_url) ? ($this->_app->getUrl()."/") : "")."$request");
			elseif(($request instanceof Route)&&is_string($request->getHandle()))
				header("Location: ".$this->_app->getUrl()."/".$request->getHandle());
		}
	}
