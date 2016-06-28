<?php
	namespace Application;
	$app=\xTend\Core\getCurrentApp(__NAMESPACE__);
	$app->getRouter()->home("My homepage");
	$app->getRouter()->error(0x0194, '404 - Page Not Found');
