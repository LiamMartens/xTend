<?php
	namespace xTend
	{
		//Core/Config.php is automatically not updated except for the version number
		Updater::ExcludeFiles(array(
			"Config/Routes.php",
			"Config/Sass.php",
			"Config/Updater.php",
			"Config/Wow.php"
		));
		Updater::ExcludeDirectories(array("Config"));
		//check for update
		//Updater::Check();
	}