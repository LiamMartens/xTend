<?php
    namespace xTend\Application;
    $bench=\xTend\Workbench\getCurrentBench(__NAMESPACE__);
    //comands
    $bench->registerCommand('/init/i', function($app) {
        $conf = $app->getFileHandler()->system("Config.Sessions.Sessions.json");
        $session_config = json_decode($conf->read(), true);
        $session_config["SessionName"] = base64_encode(random_bytes(8));
        $session_config["InitiatedKey"] = base64_encode(random_bytes(8));
        $session_config["UserAgentKey"] = base64_encode(random_bytes(8));
        $session_config["Salt"] = base64_encode(random_bytes(8));
        $session_config["UserSessionsKey"] = base64_encode(random_bytes(8));
        $session_config["UserCookiesKey"] = base64_encode(random_bytes(8));
        $conf->write(json_encode($session_config));
    }, 'init');

    $bench->registerCommand('/routes/i', function($app) {
        $routes = $app->getRouter()->getRoutes();
        foreach($routes as $route) {
            echo "/".$route->getHandle()."\t".$route."\n";
        }
    });
    
    $bench->registerCommand('/help/i', function() {
        echo "xTend CLI\n";
        echo "init\tinitializes xTend with secure keys\n";
        echo "routes\tdisplays the registered routes (only those NOT under a restrict)\n\n";
    }, 'help');
