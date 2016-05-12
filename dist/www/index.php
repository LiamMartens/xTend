<?php
	namespace xTend\Application;
	require_once("../System/Core/App.php");
	//this allows for multiple apps
	$app=\xTend\Core\createNewApp(__NAMESPACE__, __DIR__);
	$app->getFileHandler()->system("Config.App.App.php")->include();
	$app->run();
