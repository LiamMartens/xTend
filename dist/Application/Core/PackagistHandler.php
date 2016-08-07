<?php
    namespace Application\Core;

    /**
    * The PackagistHandler handles installing
    * and removing packages
    */
    class PackagistHandler {
        /** @var array Contains all installed packages */
        private static $_packages;
        /** @var array Contains the autoload spec */
        private static $_autoload;

        /**
        * @return array
        */
        public static function packages() {
            return self::$_packages;
        }

        /**
        * @return array
        */
        public static function autoload() {
            return self::$_autoload;
        }

        /**
        * Initializes packagist handler
        */
        public static function start() {
            self::$_packages = json_decode(FileHandler::system('packagist.json')->read(), true);
            self::$_autoload = json_decode(App::libs()->file('Packagist.autoload.json')->read(), true);
            App::libs()->file('Packagist.autoload.php')->include();
        }

        /**
        * @return boolean
        */
        private static function save() {
            return (FileHandler::system('packagist.json')->write(json_encode(self::$_packages))&&
                    App::libs()->file('Packagist.autoload.json')->write(json_encode(self::$_autoload)));
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
            $package_info = json_decode(file_get_contents("https://packagist.org/packages/$package_name.json"), true);
            if(!isset($package_info['status'])) {
                $to_install = false;
                //has package already been added to the app's packagist file
                $exists = isset(self::$_packages[$package_name]) ? self::$_packages[$package_name] : false;
                //check for matching version either new or existing ugradable
                foreach($package_info["package"]["versions"] as $version => $information) {
                    if(($version_param!==false)&&((new VersionCheck($version_param, $version))->match()==true)) {
                        $to_install = $information;
                        self::$_packages[$package_name]=$version_param;
                        self::save();
                        break;
                    } elseif(($exists===false)&&(substr($version, 0, 4)!=="dev-")&&($version_param===false)) {
                        $to_install = $information;
                        self::$_packages[$package_name]='^'.trim($to_install["version"], 'v');
                        self::save();
                        break;
                    } elseif(($exists!==true)&&((new VersionCheck($exists, $version))->match()==true)&&($version_param===false)) {
                        $to_install = $information;
                        break;
                    }
                }
                if($to_install!==false) {
                    echo "Downloading $package_name ".$to_install["version"]."\n";
                    //download requires
                    foreach($to_install["require"] as $rpackage => $version) {
                        if($rpackage=="php") {
                            $php_vmatch = []; preg_match('/([\>\=]+)([0-9\.]+)/', $version, $php_vmatch);
                            if(version_compare(phpversion(), $php_vmatch[2], $php_vmatch[1])==false) {
                                die("PHP version $version required"); }
                        } elseif(strpos($rpackage, '/')!==false) {
                            self::install($rpackage, $version, false);
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
                    if(isset($to_install['autoload'])) {
                        self::$_autoload["$name-$id"] = $to_install["autoload"]; }
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
        public static function remove($package_name) {
            if(isset(self::$_packages[$package_name])) {
                $version_installed = self::$_packages[$package_name];
                //remove from packagist
                unset(self::$_packages[$package_name]);
                self::save();
                //get package info
                $package_info = json_decode(file_get_contents("https://packagist.org/packages/$package_name.json"), true);
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
                $to_install = false;
                foreach($package_info["package"]["versions"] as $pack_v => $information) {
                    if((new VersionCheck($version_installed, $pack_v))->match()==true) {
                        $to_install = $information;
                        break;
                    } elseif(substr($version, 0, 4)!=="dev-") {
                        $to_install = $information;
                        break;
                    }
                }

                $notice = "";
                foreach($to_install["require"] as $p => $v) {
                    if(strpos($p, '/')!==false) {
                        $notice .= "$p : $v\n";
                    }
                }
                if($notice!="") {
                    echo "Don't forget, following packags were required by $package_name but might not be needed anymore\n(You can run 'autoremove $package_name $version_installed' or 'autoremove $package_name' to remove them automatically\n";
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
        public static function autoremove($package_name, $package_version = false) {
            $package_info = json_decode(file_get_contents("https://packagist.org/packages/$package_name.json"), true);
            //get to_install
            $to_install = false;
            foreach($package_info["package"]["versions"] as $version => $information) {
                if(($package_version!==false)&&((new VersionCheck($package_version, $version))->match()==true)) {
                    $to_install = $information;
                    break;
                } elseif((substr($version, 0, 4)!=="dev-")&&($package_version===false)) {
                    $to_install = $information;
                    break;
                }
            }
            //run through require and remove whats possible
            if($to_install!==false) {
                $to_install["require"] = array_reverse($to_install["require"]);
                foreach($to_install["require"] as $p => $v) {
                    if(strpos($p, '/')!==false) {
                        self::remove($p);
                    }
                }
            }
        }
    }
