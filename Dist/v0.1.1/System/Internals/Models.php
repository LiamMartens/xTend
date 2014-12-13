<?php
	abstract class Models
	{
		public static function Exists($model) {
			return File::Exists(App::System("Models.$model").".php");
		}
	
		public static function Init($model) {
			if(Self::Exists($model)) {
				App::Inc("Models.$model");
				App::Model(new $model());
				return true;
			}
			return false;
		}
	}
?>