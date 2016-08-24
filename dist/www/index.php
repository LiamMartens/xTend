<?php
    namespace {
        use xTend\Workbench\Workbench;
        require_once(__DIR__.'/../CLI/Core/Workbench.php');
        Workbench::configure();
        //first match all
        global $matched_application;
        $matched_application=false;
        foreach(Workbench::get('applications') as $name => $restrictions) {
            if(Workbench::match($restrictions))
                $matched_application=$name;
        }
        if($matched_application!==false) {
            require_once(__DIR__.'/../'.$matched_application.'/Core/App.php');
            $matched_application = Workbench::namespace($matched_application);
        }
    }
    namespace Application {
        global $matched_application;
        if(__NAMESPACE__==$matched_application) {
            Core\App::start(__DIR__);
            Core\FileHandler::system('Config.App.App.php')->include();
            Core\App::run();
        }
    }