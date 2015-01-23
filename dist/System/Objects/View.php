<?php
	namespace xTend
	{
		class View extends BaseDataExtension {
			//Config variables
			private $_Name = "";
			private $_FileName = "";
			private $_Exists = false;
			private $_IsWow = false;

			public function __construct($View) {
				$this->_Name = $View;
				$WowPath = File::System("Views.$View.wow").".php";
				$PhpPath = File::System("Views.$View.php");
				//Check files
				if(File::Exists($WowPath)) {
					$this->_Exists = true;
					$this->_IsWow = true;
					$this->_FileName = $WowPath;
					return true;
				} else if(File::Exists($PhpPath)) {
					$this->_Exists = true;
					$this->_IsWow = false;
					$this->_FileName = $PhpPath;
					return true;
				}
				return false;
			}

			public function __get($Name) {
				if(property_exists($this, "_$Name")) {
					return $this->{"_$Name"};
				}
				return false;
			}
		}
	}
?>