<?php
    /**
    * Register content types to the requestHandler
    */
    namespace Application;
    $app=\xTend\Core\getCurrentApp(__NAMESPACE__);
    $requestHandler = $app->getRequestHandler();
    $requestHandler->registerContentType('json', 'application/json');
    $requestHandler->registerContentType('jpg', 'image/jpeg');
    $requestHandler->registerContentType('png', 'image/png');
    $requestHandler->registerContentType('gif', 'image/gif');
    $requestHandler->registerContentType('xml', 'application/xml');
    $requestHandler->registerContentType('py', 'text/x-script.python');
    $requestHandler->registerContentType('cpp', 'text/x-c');
    $requestHandler->registerContentType('html', 'text/html');
    $requestHandler->registerContentType('css', 'text/css');
