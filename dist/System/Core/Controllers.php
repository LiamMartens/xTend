<?php
	namespace xTend
	{
		class Controllers {
			//Check whether the controller exists
			public static function Exists($Controller) {
				return File::Exists(File::System("Controllers.$Controller.php"));
			}
			//Load a controller
			public static function Initialize($Controller, $Param1 = true, $Param2 = array()) {
				//check $Param1 and $Param2
				$CreateInstance;
				$Data = array();
				if(is_bool($Param1)) { $CreateInstance = $Param1; } elseif(is_bool($Param2)) { $CreateInstance = $Param2; }
				if(is_array($Param1)) { $Data = $Param1; } elseif(is_array($Param2)) { $Data = $Param2; }
				//instantiate controller
				$Path = explode("@", $Controller);
				//Check whether the controller exists
				if(self::Exists($Path[0])) {
					$PathController = $Path[0];
					$ControllerName = explode('.', $PathController);
					$ControllerName = $ControllerName[count($ControllerName) - 1];
					//Include controller
					App::IncludeFile("Controllers.$PathController.php");
					//Create new Controller
					if($CreateInstance==true) {
						$Instance = new $ControllerName();
						//Set data
						if(is_subclass_of($Instance, "xTend\BaseDataExtension")) {
							foreach ($Data as $Key => $Value) {
								$Instance->SetData($Key, $Value);
							}
						}
						//Set App Controller
						App::Controller($Instance);
					} else {
						if(is_subclass_of($PathController, "xTend\StaticBaseDataExtension")) {
							foreach ($Data as $Key => $Value) {
								call_user_func("$PathController::SetData", $Key, $Value);
							}	
						}
					}
					//Execute requested methdos
					for($i=1;$i<count($Path);$i++) {
						//Check whether method exists
						if(method_exists((($CreateInstance==true) ? $Instance : $PathController), $Path[$i])) {
							//Execute
							if($CreateInstance==true) {
								call_user_func(array($Instance, $Path[$i]));
							} else { call_user_func("$PathController::".$Path[$i]); }
						}
					}
					return true;
				}
			}
		}
	}