<?php
	namespace xTend\Application
	{
		$app=\xTend\getCurrentApp(__NAMESPACE__);
		$app->getRouter()->home("My homepage");
	}
