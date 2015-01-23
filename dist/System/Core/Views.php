<?php
	namespace xTend
	{
		class Views {
			//Check whether a view exists
			public static function Exists($View) {
				if(
					File::Exists(File::System("Views.$View.php")) ||
					File::Exists(File::System("Views.$View.wow").".php")
				) {
					return true;
				}

			}
			//Load view
			public static function Initialize($View, $Data = array()) {
				//Check whether view exists
				if(self::Exists($View)) {
					$Instance = new View($View);
					//Append data
					if(is_subclass_of($Instance, "BaseDataExtension")) {
						foreach($Data as $Key => $Value) {
							$Instance->SetData($Key,$Value);
						}
					}
					//Set App View
					App::View($Instance);
					return true;
				}
				return false;
			}
		}
	}
?>