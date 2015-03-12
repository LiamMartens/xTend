<?php
	namespace xTend
	{
		class Modules
		{
			public static function Insert($ModuleName) {
				if(File::Exists(File::System("Modules.$ModuleName.wow").".php")) {
					$cnts = File::Read(File::System("Modules.$ModuleName.wow").".php");
					$cnts = Wow::Compile($cnts);
					eval('?>'.$cnts);
				}elseif(File::Exists(File::System("Modules.$ModuleName.php"))) {
					require_once(File::System("Modules.$ModuleName.php"));	
				}
				return false;
			}
		}
	}