<?php
    namespace Application\Core;
    use Application\Objects\ViewHandler\View;

    /**
    * The ViewHandler handles loading views
    */
    class ViewHandler {
        /** @var array Contains all loaded views */
        private static $_views=[];

        /**
        * Checks whether the view exists
        *
        * @param string $view
        *
        * @return boolean
        */
        public static function exists($view) {
            if(App::views()->file($view.'.php')->exists()||App::views()->file($view.'.wow.php', 2)->exists()) {
                return true;
            } return false;
        }

        /**
        * Loads a new view
        *
        * @param string $view
        * @param array $data
        * @param string|boolean $version
        * @param string|boolean $viewClass
        *
        * @return boolean
        */
        public static function load($view, $data = [], $version = false, $viewClass = false) {
            if(self::exists($view)) {
                //by default the view object extends BaseDataView,
                //you can define to use your own viewclass by setting the parameter
                //this will be a name and namespace of a class to use custom view classes
                //ex. xTend\FooBar
                self::$_views[$view] = ($viewClass==false) ? (new View($view, $version)) : (new $viewClass($view, $version));
                if(($data!=null)&&(count($data)>0)) {
                    if(method_exists(self::$_views[$view], 'set')) {
                        foreach ($data as $key => $value) {
                            self::$_views[$view]->set($key, $value);
                        }
                    }
                }
                //call view execute method
                if(method_exists(self::$_views[$view], 'execute')) {
                    self::$_views[$view]->execute();
                }
            }
            return false;
        }

        /**
        * Returns the loaded view by name or the first one
        *
        * @param string|boolean $viewName
        *
        * @return view|boolean
        */
        public static function find($viewName = false) {
            //the controller name here also does not include any @ functions
            if(($viewName==false)&&(count(self::$_views)==1))
                return self::$_views[array_keys(self::$_views)[0]];
            elseif($viewName==false) return false;
            if(isset(self::$_views[$viewName]))
                return self::$_views[$viewName];
            return false;
        }
    }
