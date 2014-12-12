<?php
	class View extends BaseDataExtension
	{
		private $_Name;
		private $_FileName;
		private $_Exists;
		private $_IsWow;
	
		public function __construct($viewName) {
			$this->_Name = $viewName;
			$this->_FileName = App::System("Views.$viewName");
			if(File::Exists($this->_FileName).".php") {
				$this->_Exists = true;
				$this->_IsWow = false;
			} elseif(File::Exists($this->_FileName).".wow.php") {
				$this->_Exists = true;
				$this->_IsWow = false;
			} else {
				$this->_Exists = false;
				$this->_IsWow = false;
			}
		}
	}
?>