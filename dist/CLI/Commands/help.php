<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Contains help command
    */
    Workbench::register('^help$', function($argv) {
        // contains help information
        $help = [
            'init' => 'Initializes xTend application with secure keys',
            'init show' => 'Shows xTend session configuration',
            'routes' => 'Displays the routes of the currently selected application (only those NOT under a restrict)',
            'set:public [Name]' => 'Moves the public directory',
            'set:application [Name]' => 'Sets the current workbench application',
            'new [AppName] [Domain or any] [Path or any]' => 'Creates a new application in the current project by certain domain or path restrictions',
            'add [AppName] [Domain or any] [Path or any]' => 'Adds an existing application to the project',
            'remove [AppName]' => 'Removes an application',
            'rename [AppName]' => 'Renames your application',
            'new:controller [ControllerName]' => 'Creates a new default controller',
            'new:controller [ControllerName] empty' => 'Creates a new empty controller',
            'new:controller [ControllerName] json' => 'Creates a new respond contoller',
            'new:model [ModelName]' => 'Creates a new default ORM \'connected\' model',
            'new:model [ModelName] empty' => 'Creates a new empty model',
            'new:layout [LayoutName]' => 'Creates a new basic layout',
            'config' => 'Shows the current application\'s configuration',
            'config [Name]' => 'Gets a certain configuration variable from the application\'s configuration',
            'config [Name] [Value]' => 'Sets a configuration variable of the current application',
            'wow:flavor [HTML,AT_SIGN,COMBINED]' => 'Sets the current WOW flavor',
            'packagist:install' => 'Installs packages from packagist.json file',
            'packagist:install [Vendor/Package]' => 'Installs a packagist package',
            'packagist:update [Vendor/Package]' => 'Updates a packagist package',
            'packagist:install [Vendor/Package] [Version]' => 'Installs a packagist package of a specific version',
            'packagist:remove [Vendor/Package]' => 'Removes a packagist package',
            'packagist:autoremove [Vendor/Package]' => 'Removes the dependencies of a certain package',
            'packagist:autoremove [Vendor/Package] [Version]' => 'Removes the dependencies of a certain package of a certain version',
            'packagist:autoremove [Vendor/Package] recursive' => 'Removes the dependencies, and the dependencies of the dependencies',
            'packagist:autoremove [Vendor/Package] [Version] recursive' => 'Removes the dependencies of a certain package and the dependencies of the dependencies',
            'phinx' => 'Show the integrated phinx command line help',
            'phinx [Command] ...' => 'Execute phinx command\'s right form the workbench',
            'help' => 'Shows information about the workbench\'s commands'
        ];
        
        // checks for a command similarity match
        $command_match=[]; $match_percent=0;
        foreach(Workbench::$commands as $name => $command) {
            similar_text(Workbench::$command, $name, $match_percent);
            if(($match_percent>50)&&($match_percent<100)) {
                $command_match[]=$name;
            }
        }

        // if similarity found or not
        if(count($command_match)>0) {
            echo "\nDid you mean one of these: \n";
            foreach($command_match as $command) {
                echo $command."\n";
            }
        } else {
            echo "xTend CLI\n";
            foreach($help as $cm => $info) {
                echo "$cm\n  $info\n\n";
            }   
        } echo "\n";
    }, 'help');