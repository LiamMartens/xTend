<?php
	namespace xTend
	{
		class View extends BaseDataView
		{
			private $_name;
			private $_filePath;
			private $_exists;
			private $_isWow;

			public function getName() { return $this->_name; }
			public function getFilePath() { return $this->_filePath; }
			public function getExists() { return $this->_exists; }
			public function getIsWow() { return $this->_isWow; }

			public function __construct($app, $view) {
				parent::__construct($app);
				//view construct
				$this->_name = $view;
				//get wow and php paths
				$wowPath = $this->_app->getFileHandler()->systemFile("Views.$view.wow").".php";
				$phpPath = $this->_app->getFileHandler()->systemFile("Views.$view.php");
				//check files
				if($this->_app->getFileHandler()->exists($wowPath)) {
					$this->_filePath = $wowPath;
					$this->_exists = true; 
					$this->_isWow = true;
				} elseif($this->_app->getFileHandler()->exists($phpPath)) {
					$this->_filePath = $phpPath;
					$this->_exists = true; 
					$this->_isWow = false;
				} else $this->_exists = false;
			}

			public function execute() {
				//this is what happens when a view is executed
				$path=$this->_filePath;
				if($this->_isWow) {
					$path=$this->_app->getWowCompiler()->compileView($this->_filePath);
				}
				$this->_app->getFileManager()->includeFile($path);
			}
		}
	}