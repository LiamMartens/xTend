<?php
	namespace xTend\Application
	{
		$app=\xTend\getCurrentApp(__NAMESPACE__);
		$app->setUrl("http://localhost");
		/**
			Development status toggles the built in sass compiler by Leafo
		**/
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
	}