<?php
    namespace Application;
    $app=\xTend\Core\getCurrentApp(__NAMESPACE__);
    $dirHandler = $app->getDirectoryHandler();
    $fileHandler = $app->getFileHandler();
    $autoload = $app->getPackagistHandler()->getAutoload();
    //load non psr loaders
    foreach($autoload as $package => $loader) {
        $package_name = strtolower(substr($package, 0, strrpos($package, '-')));
        foreach($loader as $type => $files) {
            if(substr($type, 0, 4)!=='psr-') {
                foreach($files as $file) {
                    $split = explode(".", $file);
                    $fileHandler->system("Libs.Packagist.$package_name.$package.$file", count($split))->include();
                }
            }
        }
    }
    spl_autoload_register(function($class) {
        $directory = str_replace('\\', '/', __DIR__);
        $namespace = substr($directory, 0, strrpos($directory, '/'));
        $namespace = substr($namespace, 0, strrpos($namespace, '/'));
        $namespace = substr($namespace, strrpos($namespace, '/')+1);
        $app = \xTend\Core\getCurrentApp($namespace);
        $dirHandler = $app->getDirectoryHandler();
        $autoload = $app->getPackagistHandler()->getAutoload();
        foreach($autoload as $package => $loader) {
            $package_name = strtolower(substr($package, 0, strrpos($package, '-')));
            $package_directory = $dirHandler->system("Libs.Packagist.$package_name.$package");
            foreach($loader as $type => $files) {
                if(substr($type, 0, 4)==='psr-') {
                    foreach($files as $prefix => $file) {
                        if(substr($class, 0, strlen($prefix))==$prefix) {
                            $f = $package_directory->file("$file/$class.php");
                            if($f->exists()) {
                                $f->include();
                                return true;
                            }
                            $f = $package_directory->file("$file/".substr($class, strlen($prefix)).".php");
                            if($f->exists()) {
                                $f->include();
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    });
