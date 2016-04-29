<?php
	namespace xTend\Application;
	ini_set('display_errors', 1);
	require_once("../System/Core/App.php");
	//this allows for multiple apps
	$app=\xTend\Core\createNewApp(__NAMESPACE__, __DIR__);
	$app->setBackupsDirectory("Backups");
	$app->setBlueprintsDirectory("Blueprints");
	$app->setConfigDirectory("Config");
	$app->setControllersDirectory("Controllers");
	$app->setLayoutsDirectory("Layouts");
	$app->setLibsDirectory("Libs");
	$app->setLogsDirectory("Logs");
	$app->setMetaDirectory("Meta");
	$app->setModelsDirectory("Models");
	$app->setModulesDirectory("Modules");
	$app->setObjectsDirectory("Objects");
	$app->setViewOutputDirectory("ViewOutput");
	$app->setViewsDirectory("Views");
	$app->run();
