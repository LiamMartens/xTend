<?php
	namespace xTend\Application;
	$app=\xTend\Core\getCurrentApp(__NAMESPACE__);
	//application configuration
	$app->setUrl("http://localhost");
	$app->setDevelopmentStatus(false);
	$app->setCharset("UTF-8");
	$app->setCompanyName("My company");
	$app->setLanguage("en");
	$app->setDescription("My application's description");
	$app->setKeywords("keyword 1, keyword 2");
	$app->setAuthor("Author Name");
	$app->setCopyright("2016");
	$app->setBackupInterval("1 week");
	$app->setBackupLimit(10);
	$app->setLogLimit(30);
	//application directory setup
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
