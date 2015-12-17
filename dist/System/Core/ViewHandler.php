<?php
	namespace xTend
	{
		class ViewHandler
		{
			private $_app;
			private $_views;
			public function __construct($app) {
				$this->_app = $app;
				$this->_views = [];
				$this->_app->getErrorCodeHandler()->registerErrorCode(0x0003, "viewhandler:invalid-view-definition", "Error while trying to pass data to an initialized view. Data methods not implemented.");
				$this->_app->getErrorCodeHandler()->registerErrorCode(0x0004, "viewhandler:invalid-view-definition", "Error while trying to execute a view object. Execute method not implemented.");
			}
			public function exists($view) {
				$fh = $this->_app->getFileHandler();
				if($fh->exists($fh->systemFile("Views.$view.php"))||$fh->exists($fh->systemFile("Views.$view.wow").".php"))
					return true;
				return false;
			}
			public function loadView($view, $data = [], $viewClass = false) {
				if($this->exists($view)) {
					//by default the view object extends BaseDataView,
					//you can define to use your own viewclass by setting the parameter
					//this will be a name and namespace of a class to use custom view classes
					//ex. xTend\FooBar
					$this->_views[$view] = ($viewClass==false) ? (new View($this->_app, $view)) : (new $viewClass($this->_app, $view));
					if(($data!=null)&&(count($data)>0)) {
						if(method_exists($this->_views[$view], "setData")) {
							foreach ($data as $key => $value) {
								$this->_views[$view]->setData($key, $value);
							}
						} else { throw $this->_app->getErrorCodeHandler()->getError(0x0003)->getException(); }
					}
					//call view execute method
					if(method_exists($this->_views[$view], "execute")) {
						$this->_views[$view]->execute();
					} else { throw $this->_app->getErrorCodeHandler()->getError(0x0004)->getException(); }
				}
				return false;
			}
			public function getView($viewName) {
				//the controller name here also does not include any @ functions
				if(($viewName==false)&&(count($this->_views)==1))
					return $this->_views[array_keys($this->_views)[0]];
				elseif($viewName==false) return false;
				if(array_key_exists($viewName, $this->_views))
					return $this->_views[$viewName];
				return false;
			}
		}
	}