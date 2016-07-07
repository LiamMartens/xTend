<?php
	namespace Application;
	$app=\xTend\Core\getCurrentApp(__NAMESPACE__);
    $urlHandler = $app->getUrlHandler();
    $urlHandler->registerContentType('json', 'application/json');
    $urlHandler->registerContentType('jpg', 'image/jpeg');
    $urlHandler->registerContentType('png', 'image/png');
    $urlHandler->registerContentType('gif', 'image/gif');
    $urlHandler->registerContentType('xml', 'application/xml');
    $urlHandler->registerContentType('py', 'text/x-script.python');
    $urlHandler->registerContentType('cpp', 'text/x-c');
    $urlHandler->registerContentType('html', 'text/html');
    $urlHandler->registerContentType('css', 'text/css');
