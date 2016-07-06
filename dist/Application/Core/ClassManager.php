<?php
	namespace xTend\Core;
	/**
		This class needs to be exclusively static as even if there are multiple instances of App running at the same time
		the included or required files will remain the same and may not be included twice
	**/
	class ClassManager
	{
		public static function includeClass($classPath) {
            return require_once($classPath);
		}
		public static function includeClasses($classes) {
			foreach ($classes as $class) {
				require_once($class);
			}
		}
	}
