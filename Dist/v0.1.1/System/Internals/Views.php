<?php
	abstract class Views
	{
		private static function Exists($view) {
			if(File::Exists(App::System("Views.$view").".php")) {
				return true;
			}
			if(File::Exists(App::System("Views.$view").".wow.php")) {
				return true;
			}
			return false;
		}
	
		public static function Init($view,$data=array()) {
			if(Self::Exists($view)) {
				//Create a new instance of view
				$ViewInstance = new View($view);
				//Append Data
				if(is_subclass_of($ViewInstance, "BaseDataExtension")) {
					foreach($data as $key => $value) {
						$ViewInstance->SetData($key,$value);
					}
				}
				App::View($ViewInstance);
				//Compile the view to ViewOutput
				Wow::Parse($view);
				return true;
			}
			return false;
		}
	}
?>