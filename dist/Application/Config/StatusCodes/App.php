<?php
    namespace Application;
    $app=\xTend\Core\getCurrentApp(__NAMESPACE__);
    $statusCodeHandler = $app->getStatusCodeHandler();
    $statusCodeHandler->registerStatusCode(0x0194, "http:404", "HTTP 404: Page not found");
    $statusCodeHandler->registerStatusCode(0x0002, "controllerhandler:invalid-controller-definition", "Error while trying to pass data to an initialized controller. Data methods not implemented.");
    $statusCodeHandler->registerStatusCode(0x0003, "viewhandler:invalid-view-definition", "Error while trying to pass data to an initialized view. Data methods not implemented.");
    $statusCodeHandler->registerStatusCode(0x0004, "viewhandler:invalid-view-definition", "Error while trying to execute a view object. Execute method not implemented.");
