<?php
    namespace xTend\Objects;
    use \xTend\Blueprints\BaseDataView;
    /**
    * The View object contains the
    * current View
    */
    class View extends BaseDataView
    {
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
        public function getName() { return $this->_name; }

        /**
        * @return string
        */
        public function getFilePath() { return $this->_filePath; }

        /**
        * @return boolean
        */
        public function getExists() { return $this->_exists; }

        /**
        * @return boolean
        */
        public function getIsWow() { return $this->_isWow; }

        /**
        * @param xTend\Core\App $app
        * @param string $view
        * @param string|boolean $version
        */
        public function __construct($app, $view, $version = false) {
            parent::__construct($app);
            //view construct
            $this->_name = $view;
            //version
            $this->_version = $version;
            //get wow and php paths
            $wowPath = $this->_app->getViewsDirectory()->file("$view.wow.php", 2);
            $phpPath = $this->_app->getViewsDirectory()->file("$view.php");
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
                $path=$this->_app->getWowCompiler()->compileView($this->_filePath, $this->_app->getLayoutsDirectory(), $this->_app->getModulesDirectory());
                if($this->_version!==false) {
                    $dot_pos = strrpos($path, '.', -5);
                    $path = substr($path, 0, $dot_pos).'.v'.$this->_version.'.php';
                }
            }
            $this->_app->getFileManager()->includeFile($path);
        }
    }
