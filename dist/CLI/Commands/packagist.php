<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Installs or updates a package
    */
    Workbench::register('^packagist:(install|update) ([a-zA-Z0-9\-\_]+)\/([a-zA-Z0-9\-\_]+)$', function($argv) {
        $package_name = $argv[1];
        if(Core\PackagistHandler::install($package_name)) {
            return "Installed $package_name";
        }
        return "No suitable version was found for $package_name";
    }, 'packagist:install');

    /**
    * Installs a new package of a specific version
    */
    Workbench::register('^packagist:install ([a-zA-Z0-9\-\_]+)\/([a-zA-Z0-9\-\_]+) (.+)$', function($argv) {
        $package_name = $argv[1];
        $package_version = $argv[2];
        if(Core\PackagistHandler::install($package_name, $package_version)) {
            return "Installed $package_name";
        }
        return "No suitable version was found for $package_name";
    });

    /**
    * Installs all packages in package.json
    */
    Workbench::register('^packagist:install$', function($argv) {
        //to install or update all packages
        $packages = Core\PackagistHandler::packages();
        foreach($packages as $package => $version) {
            echo (Core\PackagistHandler::install($package, $version, false).PHP_EOL);
        }
    });

    /**
    * Removes a certain package
    */
    Workbench::register('^packagist:remove ([a-zA-Z0-9\-\_]+)\/([a-zA-Z0-9\-\_]+)$', function($argv) {
        $package_name = $argv[1];
        if(Core\PackagistHandler::remove($package_name)) {
            return "Removed $package_name";
        }
        return "Couldn't remove $package_name, maybe it wasn't installed?";
    }, 'packagist:remove');


    /**
    * Autoremoves a certain package (recursive)
    */
    Workbench::register('^packagist:autoremove ([a-zA-Z0-9\-\_]+)\/([a-zA-Z0-9\-\_]+) recursive$', function($argv) {
        $package_name = $argv[1];
        if(Core\PackagistHandler::autoremove($package_name, false, true)) {
            return "Autoremoved $package_name";
        }
        return "Couldn't autoremove $package_name";
    }, 'packagist:autoremove_recursive');

    /**
    * Autoremoves a certain package of a certain version (recursive)
    */
    Workbench::register('^packagist:autoremove ([a-zA-Z0-9\-\_]+)\/([a-zA-Z0-9\-\_]+) (.+) recursive$', function($argv) {
        $package_name = $argv[1];
        if(Core\PackagistHandler::autoremove($package_name, $argv[2], true)) {
            return "Autoremoved $package_name";
        }
        return "Couldn't autoremove $package_name";
    });

    /**
    * Autoremoves a certain package
    */
    Workbench::register('^packagist:autoremove ([a-zA-Z0-9\-\_]+)\/([a-zA-Z0-9\-\_]+)$', function($argv) {
        $package_name = $argv[1];
        if(Core\PackagistHandler::autoremove($package_name)) {
            return "Autoremoved $package_name";
        }
        return "Couldn't autoremove $package_name";
    }, 'packagist:autoremove');

    /**
    * Autoremoves a certain package of a certain version
    */
    Workbench::register('^packagist:autoremove ([a-zA-Z0-9\-\_]+)\/([a-zA-Z0-9\-\_]+) (.+)$', function($argv) {
        $package_name = $argv[1];
        if(Core\PackagistHandler::autoremove($package_name, $argv[2])) {
            return "Autoremoved $package_name";
        }
        return "Couldn't autoremove $package_name";
    });