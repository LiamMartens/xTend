<?php
	require_once("..\\System\\Core\\App.php");
	//this allows for multiple apps
	$app=xTend\createNewApp(__DIR__);
	$app->run();