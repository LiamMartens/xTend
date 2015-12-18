<?php
	namespace xTend\Application
	{
		$app=\xTend\getCurrentApp(__NAMESPACE__);
		$app->getRouter()->home(array(
			"view" => "example",
			"controller" => "ExampleController@info",
			"model" => "ExampleModel"
		));
	}
