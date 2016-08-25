<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Sets the public directory
    */
    Workbench::register('^set:public ([a-zA-Z0-9\.\_]+)$', function($argv) {
        rename(Workbench::$directory.'/'.Workbench::get('public'),
                Workbench::$directory.'/'.$argv[1]);
        Workbench::set('public', $argv[1]);
        Workbench::save();
    }, 'set:public');

    /**
    * Sets the workbench application
    */
    Workbench::register('^set:application ([a-zA-Z0-9\.\_]+)$', function($argv) {
        $name=$argv[1]; $namespace=Workbench::namespace($name);
        if(is_dir(Workbench::$directory.'/'.$name)&&
            isset(Workbench::get('applications')[$name])) {
            Workbench::set('application', $name);
            Workbench::save();
            // rename namespaces in commmand files and so on
            $commands=array_diff(scandir(Workbench::$directory.'/CLI/Commands'), ['.', '..']);
            foreach($commands as $command) {
                Workbench::filespace(Workbench::$directory.'/CLI/Commands/'.$command, 'Application', $namespace);
            }
            Workbench::filespace(Workbench::$directory.'/workbench', 'Application', $namespace);
        } else { die('The application \''.$name.'\' does not exist'); }
    }, 'set:application');