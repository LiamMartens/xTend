<?php
    namespace xTend\Core;
    use \xTend\Blueprints\BaseDataExtension;
    /**
    * The UrlHandler hanldes navigating
    * to routes or url's
    */
    class UrlHandler extends BaseDataExtension
    {
        /** @var  xTend\Objects\Route Contains te currently executed route */
        private $_route;

        /**
        * Returns the currently activated route
        *
        * @return xTend\Objects\Route
        */
        public function getRoute() { return $this->_route; }

        /**
        * Sets the currently activated route
        *
        * @param xTend\Objects\Route $route
        */
        public function setRoute($route) { $this->_route=$route; }

        private $_app;
        /**
        * @param xTend\Core\App $app
        */
        public function __construct($app) {
            $this->_app = $app;
        }

        /**
        * @param xTend\Objects\Route|string $route
        * @param array $parameters
        * @param array $data
        *
        * @return boolean
        */
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
                    if(isset($parameters[$match[3]])) {
                        $url.='/'.$parameters[$match[3]];
                    }
                } elseif(preg_match("/^(\{)([a-zA-Z0-9_]+)(\})$/", $part, $match)) {
                    if(isset($parameters[$match[2]])) {
                        $url.='/'.$parameters[$match[2]];
                    }
                } else { $url.="/$part"; }
            }
            header("Location: ".$this->_app->getUrl().$url);
            return true;
        }

        /**
        * @param xTend\Objects\Route|string $route
        * @param array $data
        * @param boolean $inc_url
        */
        public function navigate($request, $data = [], $inc_url = true) {
            //set temp data and time to live
            Session::set(session_id().'-xtend-data', json_encode($data));
            if(is_string($request)) {
                header("Location: ".(($inc_url) ? ($this->_app->getUrl()."/") : "")."$request");
            } elseif(($request instanceof Route)&&is_string($request->getHandle())) {
                header("Location: ".$this->_app->getUrl()."/".$request->getHandle()); }
        }
    }
