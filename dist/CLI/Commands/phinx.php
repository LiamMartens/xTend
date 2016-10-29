<?php
    namespace Application;
    use xTend\Workbench\Workbench;


    /**
    * Executes phinx init command
    *
    * @param $argv array
    */
    Workbench::register('^phinx init', function($argv) {
        if(!isset($argv[2])) {
            $argv[]=''.Core\App::config()->directory('ORM');
        }
        $_SERVER['argv']=$argv;
        require(__DIR__.'/../Phinx/robmorgan-phinx/bin/phinx');
    });


    /**
    * Executes phinx command
    *
    * @param $argv array
    */
    Workbench::register('^phinx', function($argv) {
        if(count($argv)>1) {
            $config_param=false; foreach($argv as $arg) {
                if((substr($arg, 0, 16)=='--configuration=')||
                    ($arg=='-c')||
                    ($arg=='--configuration')) {
                    $config_param=true;
                    break;
                }
            }

            if($config_param===false) {
                $argv[]='-c';
                $argv[]=''.Core\App::config()->file('ORM.db.yml');
            }
        }

        // create directory and exclude file
        $directory = Core\App::config()->directory('ORM.db');
        if(!$directory->exists()) {
            $directory->create();
        }
        $exclude_file=Core\App::config()->file('ORM.db..exclude', 1);
        if(!$exclude_file->exists()) {
            $exclude_file->write("");
        }

        $_SERVER['argv']=$argv;
        require(__DIR__.'/../Phinx/robmorgan-phinx/bin/phinx');
    });
