<?php
    class PhinxAutoloader {
        private static $_packagemap=false;

        public static function packagemap() {
            if(self::$_packagemap===false) {
                self::$_packagemap=json_decode(file_get_contents(__DIR__.'/autoload.json'), true);
            }
            return self::$_packagemap;
        }

        public static function start() {
            self::packagemap();
            spl_autoload_register(function($class) {
                $packages=PhinxAutoloader::packagemap();
                foreach($packages as $package => $classmap) {
                    if(isset($classmap[$class])) {
                        require(__DIR__."/$package/".$classmap[$class]);
                    }
                }
            });
            return true;
        }
    }
    return 'PhinxAutoloader::start';