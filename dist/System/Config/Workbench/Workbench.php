<?php
    namespace xTend\Application;
    $bench=\xTend\Workbench\getCurrentBench(__NAMESPACE__);
    //comands
    $bench->registerCommand('/init/i', function($app, $argv) {
        $conf = $app->getFileHandler()->system("Config.Sessions.Sessions.json");
        $session_config = json_decode($conf->read(), true);
        $session_config["SessionName"] = base64_encode(random_bytes(8));
        $session_config["InitiatedKey"] = base64_encode(random_bytes(8));
        $session_config["UserAgentKey"] = base64_encode(random_bytes(8));
        $session_config["Salt"] = base64_encode(random_bytes(8));
        $session_config["UserSessionsKey"] = base64_encode(random_bytes(8));
        $session_config["UserCookiesKey"] = base64_encode(random_bytes(8));
        $conf->write(json_encode($session_config));
    });
