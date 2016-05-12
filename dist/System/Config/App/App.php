<?php
	namespace xTend\Application;
	$app=\xTend\Core\getCurrentApp(__NAMESPACE__);
	//application configuration
	$app->configuration(json_decode($app->getFileHandler()->system("Config.App.Configuration.json")->read(), true));
	//application directory setup
	$app->directories(json_decode($app->getFileHandler()->system("Config.App.Directories.json")->read(), true));
