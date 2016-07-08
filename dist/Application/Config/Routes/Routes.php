<?php
    /**
    * Registers the routes. This is a default file
    * but it can also be done in a different file
    */
    namespace Application;
    $app=\xTend\Core\getCurrentApp(__NAMESPACE__);
    $app->getRouter()->home("My homepage");
    $app->getRouter()->error(0x0194, '404 - Page Not Found');
