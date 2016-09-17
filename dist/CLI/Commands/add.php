<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Adds an existing app to the project
    *
    * @param $argv array
    */
    Workbench::register('^add ([a-zA-Z0-9\.\_]+) (.+?) (.+?)$', function($argv) {
        // get application name
        $name=$argv[1];
        // get domain and path restrictions
        $domain=($argv[2]==='any') ? '*' : $argv[2];
        $path=($argv[3]==='any') ? '*' : $argv[3];
        // check for existance
        if(!is_dir($name)) { die('Application folder not found'); }
        // get namespace from name
        $namespace=Workbench::namespace($name);
        // add to index.php
        file_put_contents(Workbench::$directory.'/'.Workbench::get('public').'/index.php', '
    namespace '.$namespace.' {
        global $matched_application;
        if(__NAMESPACE__==$matched_application) {
            Core\App::start(__DIR__);
            Core\FileHandler::system(\'Config.App.App.php\')->include();
            Core\App::run();
        }
    }', FILE_APPEND);
        // add application to config
        Workbench::new($name, $domain, $path);
    });