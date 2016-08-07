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
    $autoload = PackagistHandler::autoload();
    //load non psr loaders
    foreach($autoload as $package => $loader) {
        $package_name = strtolower(substr($package, 0, strrpos($package, '-')));
        foreach($loader as $type => $files) {
            if(substr($type, 0, 4)!=='psr-') {
                foreach($files as $file) {
                    $split = explode(".", $file);
                    App::libs()->file("Packagist.$package_name.$package.$file", count($split) - 1)->include();
                }
            }
        }
    }
    spl_autoload_register(function($class) {
        $autoload=PackagistHandler::autoload();
        foreach($autoload as $package => $loader) {
            $package_name = strtolower(substr($package, 0, strrpos($package, '-')));
            $package_directory = __DIR__."/$package_name/$package";
            foreach($loader as $type => $files) {
                if(substr($type, 0, 4)==='psr-') {
                    foreach($files as $prefix => $file) {
                        if(substr($class, 0, strlen($prefix))==$prefix) {
                            $f = $package_directory."/$file/$class.php";
                            if(file_exists($f)) {
                                include($f);
                                return true;
                            }
                            $f = $package_directory.("/$file/".substr($class, strlen($prefix)).".php");
                            if(file_exists($f)) {
                                include($f);
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    });
