<?php
	namespace xTend
	{
		class Models {
			//Model exists
			public function Exists($Model) {
				return File::Exists(File::System("Models.$Model.php"));
			}
			//Initialize a model
			public static function Initialize($Model) {
				//Check whether model exists
				if(Self::Exists($Model)) {
					//Include model
					App::IncludeFile("Models.$Model.php");
					App::Model(new $Model());
					return true;
				}
				return false;
			}
		}
	}
?>