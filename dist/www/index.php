<?php
    namespace {
        use \xTend\Workbench\Workbench as Workbench;
        require_once('../CLI/Workbench.php');
        Workbench::loadConfiguration();
        //first match all
        global $matched_application;
        $matched_application=false;
        foreach(Workbench::getConfiguration('applications') as $name => $restrictions) {
            Workbench::setNamespace(str_replace('.', '\\', $name));
            if(Workbench::isAppMatch())
                $matched_application=$name;
        }
        if($matched_application!==false) {
            require_once("../$matched_application/Core/App.php");
            $matched_application = str_replace('.', '\\', $matched_application);
        }
    }
    namespace Application {
        use \xTend\Workbench\Workbench as Workbench;
        Workbench::setNamespace(__NAMESPACE__);
        global $matched_application;
        if(__NAMESPACE__==$matched_application) {
            $app=\xTend\Core\createNewApp(__NAMESPACE__, __DIR__);
            $app->getFileHandler()->system("Config.App.App.php")->include();
            $app->run();
        }
    }
