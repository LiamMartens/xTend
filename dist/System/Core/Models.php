<?php
	namespace xTend
	{
		class Models {
			//Model exists
			public static function Exists($Model) {
				return File::Exists(File::System("Models.$Model.php"));
			}
			//Initialize a model
			public static function Initialize($Model) {
				//Check whether model exists
				if(self::Exists($Model)) {
					//Include model
					App::IncludeFile("Models.$Model.php");
					$ModelName = explode('.', $Model);
					$ModelName = $ModelName[count($ModelName) - 1];
					App::Model(new $ModelName());
					return true;
				}
				return false;
			}
		}
	}
?>