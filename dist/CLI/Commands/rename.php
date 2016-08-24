<?php
    namespace Application;
    use \RecursiveIteratorIterator;
    use \RecursiveDirectoryIterator;
    use xTend\Workbench\Workbench;

    Workbench::register('^rename ([a-zA-Z0-9\.\_]+)$', function($argv) {
        $name=$argv[1]; $namespace=Workbench::namespace($name);
        // do check
        if(isset(Workbench::get('applications')[$name])) { die('Application name already used'); }
        // rename
        rename(Workbench::$directory.'/'.Workbench::get('application'), Workbench::$directory.'/'.$name);
        // replace old namespaces (default Application)
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Workbench::$directory.'/'.$name)) as $file) {
            // skip if not file / if not PHP file
            if((!is_file($file))||(substr($file, strrpos($file, '.'))!=='.php')) { continue; };
            Workbench::filespace($file, 'Application', $namespace);
        }
        // rename application in configuration
        $restrictions=Workbench::get('applications')[Workbench::get('application')];
        unset(Workbench::$configuration['applications'][Workbench::get('application')]);
        Workbench::$configuration['applications'][$name]=$restrictions;
        // set application
        Workbench::$commands['set:application']->execute($argv);
    }, 'rename');