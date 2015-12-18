<?php
	namespace xTend\Application
	{
		require_once("..\\System\\Core\\App.php");
		//this allows for multiple apps
		$app=\xTend\createNewApp(__NAMESPACE__, __DIR__);
		$app->run();
	}
