<?php
    namespace {
        use xTend\Workbench;
        require_once(__DIR__.'/../CLI/Workbench.php');
        Workbench::configuration();
        //first match all
        global $matched_application;
        $matched_application=false;
        foreach(Workbench::get('applications') as $name => $restrictions) {
            Workbench::namespace(str_replace('.', '\\', $name));
            if(Workbench::match($name))
                $matched_application=$name;
        }
        if($matched_application!==false) {
            require_once('../'.$matched_application.'/Core/App.php');
            $matched_application = str_replace('.', '\\', $matched_application);
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