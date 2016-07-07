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

        private $_contentType;
        protected $_contentTypes = [];
        public function registerContentType($ext, $type) { $this->_contentType[$ext] = $type; }
        public function getContentType() { return $this->_contentType; }
        public function setContentType($type) {
            $ct = $type;
            if(isset($this->_contentType[$type])) {
                $ct=$this->_contentType[$ct];
            }
            $this->_contentType = $ct;
            header("Content-Type: $ct");
            return $ct;
        }

		private $_app;
		public function __construct($app) {
			$this->_app = $app;
		}

		public function to($route, $parameters = [], $data = []) {
			Session::set(session_id().'-xtend-data', json_encode($data));
			$handle='';
			if(is_string($route)) {
				//by route name
				$handle=$this->_app->getRouter()->getRouteByAlias($route)->getHandle();
			} elseif(($route instanceof Route)&&is_string($route->getHandle())) {
				//by route object
				$handle=$route->getHandle();
			}
			$url = ''; $parts = explode('/', $handle);
			foreach ($parts as $part) {
				$match=[];
				if(preg_match("/^(rx)(\{)([a-zA-Z0-9_]+)(\})(\{)(.*)(\})$/", $part, $match)) {
					if(array_key_exists($match[3], $parameters)) {
						$url.='/'.$parameters[$match[3]];
					}
				} elseif(preg_match("/^(\{)([a-zA-Z0-9_]+)(\})$/", $part, $match)) {
					if(array_key_exists($match[2], $parameters)) {
						$url.='/'.$parameters[$match[2]];
					}
				} else { $url.="/$part"; }
			}
			header("Location: ".$this->_app->getUrl().$url);
			return true;
		}

		public function navigate($request, $data = [], $inc_url = true) {
			//set temp data and time to live
			Session::set(session_id().'-xtend-data', json_encode($data));
			if(is_string($request)) {
				header("Location: ".(($inc_url) ? ($this->_app->getUrl()."/") : "")."$request");
			} elseif(($request instanceof Route)&&is_string($request->getHandle())) {
				header("Location: ".$this->_app->getUrl()."/".$request->getHandle()); }
		}
	}
