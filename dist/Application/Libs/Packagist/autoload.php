<?php
    /**
    * This file handles autoloading
    * packagist libraries
    */
    namespace Application;
    use Application\Core\App;
    use Application\Core\DirectoryHandler;
    use Application\Core\FileHandler;
    use Application\Core\PackagistHandler;
    use Application\Core\FileManager;
    //load non psr loaders
    spl_autoload_register(function($class) {
        $autoload=PackagistHandler::autoload();
        foreach($autoload as $package => $loader) {
            $package_name = strtolower(substr($package, 0, strrpos($package, '-')));
            $package_directory = __DIR__."/$package_name/$package/";
            if(isset($loader[$class])&&is_file($package_directory.$loader[$class])) {
                FileManager::include($package_directory.$loader[$class]);
            }
        }
        return false;
    });
