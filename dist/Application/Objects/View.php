<?php
    namespace xTend\Objects;
    use \xTend\Blueprints\BaseDataView;
    class View extends BaseDataView
    {
        private $_name;
        private $_filePath;
        private $_version;
        private $_exists;
        private $_isWow;

        public function getName() { return $this->_name; }
        public function getFilePath() { return $this->_filePath; }
        public function getExists() { return $this->_exists; }
        public function getIsWow() { return $this->_isWow; }

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
