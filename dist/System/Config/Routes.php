<?php
	namespace xTend
	{
		$app=getCurrentApp(__DIR__);
		$app->getRouter()->home(array(
			"view" => "example",
			"controller" => "ExampleController@info",
			"model" => "ExampleModel"
		));
	}