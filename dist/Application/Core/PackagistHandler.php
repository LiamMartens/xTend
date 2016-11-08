<?php
    namespace Application\Core;

    /**
    * The PackagistHandler handles installing
    * and removing packages
    */
    class PackagistHandler {
        const URL = "https://packagist.org/packages";
        /** @var array Contains all installed packages */
        private static $_packages=false;
        /** @var array Contains the autoload spec */
        private static $_autoload=false;

        /**
        * @return array
        */
        public static function packages() {
            if(self::$_packages==false) {
                self::$_packages = json_decode(FileHandler::system('packagist.json')->read(), true);
            }
            return self::$_packages;
        }

        /**
        * @return array
        */
        public static function autoload() {
            if(self::$_autoload==false) {
                self::$_autoload = json_decode(App::libs()->file('Packagist.autoload.json')->read(), true);
            }
            return self::$_autoload;
        }

        /**
        * Initializes packagist handler
        */
        public static function start() {
            self::packages();
            self::autoload();
            App::libs()->file('Packagist.autoload.php')->include();
        }

        /**
        * @return boolean
        */
        private static function save() {
            return (FileHandler::system('packagist.json')->write(json_encode(self::$_packages))&&
                    App::libs()->file('Packagist.autoload.json')->write(json_encode(self::$_autoload)));
        }

        private static function match($package_info, $version_spec = false) {
            foreach($package_info["package"]["versions"] as $version => $information) {
                if(($version_spec!==false)&&((new VersionCheck($version_spec, $version))->match()==true)) {
                    return $information;
                } elseif((substr($version, 0, 4)!=='dev-')&&($version_spec===false)) {
                    return $information;
                }
            }
            return false;
        }

        /**
        * Installs a packagist package
        *
        * @param string $package_name
        * @param string|boolean $version_param
        * @param boolean-
        *
        * @return boolean
        */
        public static function install($package_name, $version_param = false, $die_on_duplicate = true) {
            $package_info = json_decode(file_get_contents(self::URL."/$package_name.json"), true);
            if(!isset($package_info['status'])) {
                //has package already been added to the app's packagist file
                $exists = isset(self::$_packages[$package_name]) ? self::$_packages[$package_name] : false;
                //check for matching version either new or existing ugradable
                $to_install = self::match($package_info, $version_param);
                if($to_install!==false) {
                    self::$_packages[$package_name]='^'.trim($to_install["version"], 'v');
                    self::save();
                } elseif(($to_install===false)&&($exists!==true)&&($exists!==false)) {
                    $to_install = self::match($package_info, $exists);
                }
                if($to_install!==false) {
                    echo "Downloading $package_name ".$to_install["version"]."\n";
                    //download requires
                    if(isset($to_install["require"])) {
                        foreach($to_install["require"] as $rpackage => $version) {
                            if($rpackage=="php") {
                                $php_vmatch = []; preg_match('/([\>\=]+)\s*([0-9\.]+)/', $version, $php_vmatch);
                                if(version_compare(phpversion(), $php_vmatch[2], $php_vmatch[1])==false) {
                                    die("PHP version $version required"); }
                            } elseif(strpos($rpackage, '/')!==false) {
                                self::install($rpackage, $version, false);
                            }
                        }
                    }
                    //get directory names and create if not existing yet
                    $name=$to_install["source"]["url"];
                    $name=substr($name, strlen('https://github.com/'));
                    $name=str_replace('/', '-', substr($name, 0, strrpos($name, '.')));
                    $id=substr($to_install["dist"]["reference"], 0, 7);
                    $package_directory = App::libs()->directory('Packagist.'.strtolower($name));
                    $package_sub = $package_directory->directory("$name-$id");
                    if($package_sub->exists()) {
                        if($die_on_duplicate) {
                            die("Package version already installed\n");
                        } else {
                            echo "Package version already installed\n";
                            return false;
                        }
                    }
                    if(!$package_directory->exists()) { $package_directory->create(); }
                    //download file
                    $package_zip = (string)FileHandler::system('package.zip');
                    $fp = fopen($package_zip, 'w');
                    $ch = curl_init($to_install["dist"]["url"]);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                    curl_exec($ch);
                    curl_close($ch);
                    fclose($fp);
                    //extract package.zip
                    $zip=new \ZipArchive;
                    $zip->open($package_zip);
                    $zip->extractTo($package_directory);
                    //remove zip
                    unlink($package_zip);
                    //add .exclude file
                    $package_sub->file(".exclude")->write('');
                    //get existing installed version
                    $prev_versions = $package_directory->directories();
                    //remove any existing key from autoload
                    $prev_version_found = false;
                    foreach($prev_versions as $prev_version) {
                        $prev_version_name = $prev_version->name();
                        if(($prev_version_name!=="$name-$id")&&isset(self::$_autoload[$prev_version_name])) {
                            $prev_version_found = $prev_version_name;
                            unset(self::$_autoload[$prev_version_name]);
                        }
                    }
                    //let the user know the previous version has been removed from autoload
                    if($prev_version_found!==false) {
                        echo "A previously installed version was detected and removed from the autoload.json in favor of the new version. The package files still exist in Libs/Packagist/$name/$prev_version_found\n";
                    }
                    //add autoload
                    $classmap=[];
                    if(isset($to_install['autoload'])) {
                        // go through autoload
                        foreach($to_install['autoload'] as $spec => $mapping) {
                            if($spec=='classmap') {
                                // loop through the files and fetch the fully qualified classnames
                                // through regex
                                foreach($mapping as $file) {
                                    $f=$package_sub->file($file, substr_count($file, '.'));
                                    $classes=$f->classes();
                                    foreach($classes as $class) {
                                        $classmap[$class]=$file;
                                    }
                                }
                            } else if($spec=='files') {
                                if(!isset($classmap['files'])) {
                                    $classmap['files']=[];
                                }
                                foreach($mapping as $file) {
                                    $classmap['files'][]=$file;
                                }
                            } else {
                                // psr -> go through mapping
                                foreach($mapping as $prefix => $source) {
                                    // get all files in source
                                    $directory=$package_sub->directory($source);
                                    $files=$directory->files(true);
                                    // loop through files and add to class mapping
                                    foreach($files as $file) {
                                        $classes=$file->classes();
                                        foreach($classes as $class) {
                                            $classmap[$class]=$source.substr($file, strlen($directory));
                                        }
                                    }
                                }
                            }
                        }
                        self::$_autoload["$name-$id"] = $classmap; }
                    self::save();
                    return true;
                }
            }
            return false;
        }

        /**
        * Removes a packagist package
        *
        * @param string $^package_name
        *
        * @return boolean
        */
        public static function remove($package_name, $no_output=false) {
            if(isset(self::$_packages[$package_name])) {
                $version_installed = self::$_packages[$package_name];
                //remove from packagist
                unset(self::$_packages[$package_name]);
                self::save();
                //get package info
                $package_info = json_decode(file_get_contents(self::URL."/$package_name.json"), true);
                $git_name = $package_info["package"]["repository"];
                $git_name = substr($git_name, strrpos($git_name, '/', strrpos($git_name, '/') - strlen($git_name) - 1) + 1);
                //get all directories
                $package_directory = App::libs()->directory('Packagist.'.trim(str_replace('/', '-', strtolower($git_name))));
                $installed_versions = $package_directory->directories();
                //remove from autoload
                foreach($installed_versions as $version) {
                    if(isset(self::$_autoload[$version->name()])) {
                        unset(self::$_autoload[$version->name()]);
                    }
                    //remove version
                    $version->remove();
                }
                self::save();
                //remove remaining directory
                $package_directory->remove();
                //show packages
                $to_install = self::match($package_info, $version_installed);
                $notice = "";
                foreach($to_install["require"] as $p => $v) {
                    if(strpos($p, '/')!==false) {
                        $notice .= "$p : $v\n";
                    }
                }
                if(($notice!="")&&($no_output===false)) {
                    echo "Don't forget, following packags were required by $package_name but might not be needed anymore\n(You can run 'autoremove $package_name $version_installed' or 'autoremove $package_name' to remove them automatically".PHP_EOL;
                    echo $notice;
                }
                return true;
            }
            return false;
        }

        /**
        * Removes a packagist's dependencies
        *
        * @param string $package_name
        * @param string|boolean $package_version
        */
        public static function autoremove($package_name, $package_version = false, $recursive = false) {
            // remove package if not yet done
            self::remove($package_name, true);

            $package_info = json_decode(file_get_contents(self::URL."/$package_name.json"), true);
            //get to_install
            $to_install = self::match($package_info, $package_version);
            //run through require and remove whats possible
            if($to_install!==false) {
                $dependencies = array_reverse($to_install["require"]);
                $dep_packages = array_keys($dependencies);
                // get other package dependencies
                $packages = self::packages();
                foreach($packages as $p => $v) {
                    // don't check current package
                    // or current package deps
                    if(($p!=$package_name)&&(array_search($p, $dep_packages)===false)) {
                        $dep_info = json_decode(file_get_contents(self::URL."/$p.json"), true);
                        $match = self::match($dep_info, $v);
                        // remove the current dependencies from deps deps
                        $match["require"] = array_diff(array_keys($match["require"]), $dep_packages);
                        $dep_packages = array_diff($dep_packages, $match["require"]);
                    }
                }
                $packages_left = array_diff($dep_packages, array_keys($to_install["require"]));
                foreach($dep_packages as $p) {
                    if(strpos($p, '/')!==false) {
                        self::remove($p, true);
                        if($recursive) {
                            self::autoremove($p, $dependencies[$p], true);
                        }
                    }
                }
                if(count($packages_left)>0) {
                    echo "Following packages we're not removed as they are still required by other packages".PHP_EOL;
                    foreach($packages_left as $p) {
                        echo $p.PHP_EOL;
                    }
                }
                return true;
            }
            return false;
        }
    }
