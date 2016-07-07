<?php
    namespace xTend\Core;
    use \xTend\Objects\View as View;
    class ViewHandler
    {
        private $_app;
        private $_views;
        public function __construct($app) {
            $this->_app = $app;
            $this->_views = [];
        }
        public function exists($view) {
            $fh = $this->_app->getFileHandler();
            if($this->_app->getViewsDirectory()->file("$view.php")->exists()||$this->_app->getViewsDirectory()->file("$view.wow.php", 2)->exists())
                return true;
            return false;
        }
        public function loadView($view, $data = [], $version = false, $viewClass = false) {
            if($this->exists($view)) {
                //by default the view object extends BaseDataView,
                //you can define to use your own viewclass by setting the parameter
                //this will be a name and namespace of a class to use custom view classes
                //ex. xTend\FooBar
                $this->_views[$view] = ($viewClass==false) ? (new View($this->_app, $view, $version)) : (new $viewClass($this->_app, $view, $version));
                if(($data!=null)&&(count($data)>0)) {
                    if(method_exists($this->_views[$view], "setData")) {
                        foreach ($data as $key => $value) {
                            $this->_views[$view]->setData($key, $value);
                        }
                    } else { throw $this->_app->getStatusCodeHandler()->getStatus(0x0003)->getException(); }
                }
                //call view execute method
                if(method_exists($this->_views[$view], "execute")) {
                    $this->_views[$view]->execute();
                } else { throw $this->_app->getStatusCodeHandler()->getStatus(0x0004)->getException(); }
            }
            return false;
        }
        public function getView($viewName = false) {
            //the controller name here also does not include any @ functions
            if(($viewName==false)&&(count($this->_views)==1))
                return $this->_views[array_keys($this->_views)[0]];
            elseif($viewName==false) return false;
            if(array_key_exists($viewName, $this->_views))
                return $this->_views[$viewName];
            return false;
        }
    }
