<?php
	namespace xTend
	{
		//Core/Config.php is automatically not updated except for the version number
		Updater::ExcludeFiles(array());
		Updater::ExcludeDirectories(array("Config"));
		Updater::BackupBefore(true);
		//check for update
		//Updater::Check();
	}