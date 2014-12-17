<?php
	namespace xTend
	{
		class Controllers {
			//Check whether the controller exists
			public static function Exists($Controller) {
				return File::Exists(File::System("Controllers.$Controller.php"));
			}
			//Load a controller
			public static function Initialize($Controller, $Data = array()) {
				$Path = explode("@", $Controller);
				//Check whether the controller exists
				if(Self::Exists($Path[0])) {
					$PathController = $Path[0];
					//Include controller
					App::IncludeFile("Controllers.$PathController.php");
					//Create new Controller
					$Instance = new $PathController();
					//Set data
					if(is_subclass_of($Instance, "BaseDataExtension")) {
						foreach ($Data as $Key => $Value) {
							$Instance->SetData($Key, $Value);
						}
					}
					//Execute requested methdos
					for($i=1;$i<count($Path);$i++) {
						//Check whether method exists
						if(method_exists($Instance, $Path[$i])) {
							//Execute
							call_user_func(array($Instance, $Path[$i]));
						}
					}
					//Set App Controller
					App::Controller($Instance);
					return true;
				}
				return false;
			}
		}
	}
?>