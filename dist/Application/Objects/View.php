<?php
    namespace Application\Objects\ViewHandler;
    use Application\Core\App;
    use Application\Blueprints\DataExtension;
    use Application\Core\Wow;
    /**
    * The View object contains the
    * current View
    */
    class View extends DataExtension {
        /** @var string Contains the name of the view */
        private $_name;
        /** @var strig Contains the file path to the view */
        private $_filePath;
        /** @var string Contains the version of the view */
        private $_version;
        /** @var boolean Contains whether the view exists */
        private $_exists;
        /** @var boolean Holds whether the view is a wow template */
        private $_isWow;

        /**
        * @return string
        */
        public function name() { return $this->_name; }

        /**
        * @return string
        */
        public function file() { return $this->_filePath; }

        /**
        * @return boolean
        */
        public function exists() { return $this->_exists; }

        /**
        * @return boolean
        */
        public function wow() { return $this->_isWow; }

        /**
        * @param string $view
        * @param string|boolean $version
        */
        public function __construct($view, $version = false) {
            //view construct
            $this->_name = $view;
            //version
            $this->_version = $version;
            //get wow and php paths
            $wowPath = App::views()->file($view.'.wow.php', 2);
            $phpPath = App::views()->file($view.'.php');
            //check files
            if($wowPath->exists()) {
                $this->_filePath = $wowPath;
                $this->_exists = true;
                $this->_isWow = true;
            } elseif($phpPath->exists()) {
                $this->_filePath = $phpPath;
                $this->_exists = true;
                $this->_isWow = false;
            } else $this->_exists = false;
        }

        public function execute() {
            //this is what happens when a view is executed
            $path=$this->_filePath;
            if($this->_isWow) {
                $path=Wow::view($this->_filePath, App::layouts(), App::modules());
                if($this->_version!==false) {
                    $dot_pos = strrpos($path, '.', -5);
                    $path = substr($path, 0, $dot_pos).'.v'.$this->_version.'.php';
                }
            }
            FileManager::include($path);
        }
    }
