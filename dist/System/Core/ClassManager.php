<?php
	namespace xTend;
	/**
		This class needs to be exclusively static as even if there are multiple instances of App running at the same time
		the included or required files will remain the same and may not be included twice
	**/
	class ClassManager
	{
		public static function includeClass($className, $classPath) {
			if(!class_exists($className)) {
				require($classPath);
				return true;
			}
			return false;
		}
		public static function includeClasses($classes) {
			foreach ($classes as $class) {
				self::includeClass($class[0], $class[1]);
			}
		}
	}