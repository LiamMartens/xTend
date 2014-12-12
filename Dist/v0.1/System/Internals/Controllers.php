<?php
	abstract class Controllers
	{
		private static function Exists($controller) {
			return File::Exists(App::System("Controllers.$controller").".php");
		}
		
		public static function Init($controller,$data=array()) {
			$Path = explode("@", $controller);
			if(Self::Exists($Path[0])) {
				$PathController = $Path[0];
				App::Inc("Controllers.$PathController");
				$ControllerInstance = new $PathController();
				App::Controller($ControllerInstance);
				if(is_subclass_of($ControllerInstance,"BaseDataExtension")) {
					foreach($data as $Key => $Value) {
						$ControllerInstance->SetData($Key,$Value);
					}
				}
				if(count($Path)==2) {
					//Start method given
					if(method_exists($ControllerInstance,$Path[1])) {
						call_user_func(array($ControllerInstance,$Path[1]));
					}
				}
				return true;
			}
			return false;
		}
	}
?>