<?php
    /**
    * Registers the application's
    * status codes
    */
    namespace Application;
    use Application\Core\StatusCodeHandler;
    StatusCodeHandler::register(0x0000, 'statuscodehandler:invalid-code', 'Error in StatusCodeHandler: Trying to register an invalid code');
    StatusCodeHandler::register(0x0001, 'statuscodehandler:invalid-name', 'Error in StatusCodeHandler: Trying to register an invalid name');
    StatusCodeHandler::register(0x0194, 'http:404', 'HTTP 404: Page not found');
    StatusCodeHandler::register(0x0002, 'controllerhandler:invalid-controller-definition', 'Error while trying to pass data to an initialized controller. Data methods not implemented.');
    StatusCodeHandler::register(0x0003, 'viewhandler:invalid-view-definition', 'Error while trying to pass data to an initialized view. Data methods not implemented.');
    StatusCodeHandler::register(0x0004, 'viewhandler:invalid-view-definition', 'Error while trying to execute a view object. Execute method not implemented.');
    StatusCodeHandler::register(0x0005, 'xorm:could-not-connect', 'xORM could not connect to database');