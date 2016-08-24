<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Shows the application routes
    * (those not under restrict)
    */
    Workbench::register('^routes$', function($argv) {
        $routes = Core\Router::all();
        foreach($routes as $route) {
            echo '/'.str_pad($route->handle(), 28).$route.PHP_EOL;
        }
    }, 'routes');