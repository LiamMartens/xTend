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
        if(is_dir(self::$directory.'/'.$argv[1])&&
            isset(Workbench::get('applications')[$argv[1]])) {
            Workbench::set('application', $argv[1]);
            Workbench::save();
            // rename namespaces in commmand files and so on
            $commands=array_diff(scandir(self::$directory.'/CLI/Commands'), ['.', '..']);
            foreach($commands as $command) {
                Workbench::filespace(self::$directory.'/CLI/Commands/'.$command, 'Application', $argv[1]);
            }
            Workbench::filespace(self::$directory.'/workbench');
        } else { die('The application \''.$argv[1].'\' does not exist'); }
    }, 'set:application');