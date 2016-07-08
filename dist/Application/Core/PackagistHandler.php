<?php
    namespace xTend\Core;
    /**
    * The PackagistHandler handles installing
    * and removing packages
    */
    class PackagistHandler {
        /** @var xTend\Core\App Current application */
        private $_app;
        /** @var xTend\Core\FileHandler Contains the application's file handler */
        private $_fileHandler;
        /** @var xTend\Core\DirecotryHandler Contains the application's directory handler */
        private $_dirHandler;
        /** @var array Contains all installed packages */
        private $_packages;

        /**
        * @return array
        */
        public function getPackages() {
            return $this->_packages;
        }

        private $_autoload;
        /**
        * @return array
        */
        public function getAutoload() {
            return $this->_autoload;
        }

        /**
        * @param xTend\Core\App
        */
        public function __construct($app) {
            $this->_app = $app;
            $this->_fileHandler = $app->getFileHandler();
            $this->_dirHandler = $app->getDirectoryHandler();
            //load current packages
            $this->_packages = json_decode($this->_fileHandler->system('packagist.json')->read(), true);
            //load current autoload spec
            $this->_autoload = json_decode($this->_fileHandler->system('Libs.Packagist.autoload.json')->read(), true);
        }

        /**
        * @return boolean
        */
        private function savePackages() {
            return $this->_fileHandler->system('packagist.json')->write(json_encode($this->_packages));
        }

        /**
        * @return boolean
        */
        private function saveAutoload() {
            return $this->_fileHandler->system('Libs.Packagist.autoload.json')->write(json_encode($this->_autoload));
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
        public function install($package_name, $version_param = false, $die_on_duplicate = true) {
            $package_info = json_decode(file_get_contents("https://packagist.org/packages/$package_name.json"), true);
            if(!array_key_exists("status", $package_info)) {
                $to_install = false;
                //has package already been added to the app's packagist file
                $exists = array_key_exists($package_name, $this->_packages) ? $this->_packages[$package_name] : false;
                //check for matching version either new or existing ugradable
                foreach($package_info["package"]["versions"] as $version => $information) {
                    if(($version_param!==false)&&((new VersionCheck($version_param, $version))->isMatch()==true)) {
                        $to_install = $information;
                        $this->_packages[$package_name]=$version_param;
                        $this->savePackages();
                        break;
                    } elseif(($exists===false)&&(substr($version, 0, 4)!=="dev-")&&($version_param===false)) {
                        $to_install = $information;
                        $this->_packages[$package_name]='^'.trim($to_install["version"], 'v');
                        $this->savePackages();
                        break;
                    } elseif(($exists!==true)&&((new VersionCheck($exists, $version))->isMatch()==true)&&($version_param===false)) {
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
                            $this->install($rpackage, $version, false);
                        }
                    }
                    //get directory names and create if not existing yet
                    $name=$to_install["source"]["url"];
                    $name=substr($name, strlen('https://github.com/'));
                    $name=str_replace('/', '-', substr($name, 0, strrpos($name, '.')));
                    $id=substr($to_install["dist"]["reference"], 0, 7);
                    $package_directory = $this->_dirHandler->system("Libs.Packagist.".strtolower($name));
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
                    $package_zip = (string)$this->_fileHandler->system('package.zip');
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
                        if(($prev_version_name!=="$name-$id")&&array_key_exists($prev_version_name, $this->_autoload)) {
                            $prev_version_found = $prev_version_name;
                            unset($this->_autoload[$prev_version_name]);
                        }
                    }
                    //let the user know the previous version has been removed from autoload
                    if($prev_version_found!==false) {
                        echo "A previously installed version was detected and removed from the autoload.json in favor of the new version. The package files still exist in Libs/Packagist/$name/$prev_version_found\n";
                    }
                    //add autoload
                    if(array_key_exists("autoload", $to_install)) {
                        $this->_autoload["$name-$id"] = $to_install["autoload"]; }
                    $this->saveAutoload();
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
        public function remove($package_name) {
            if(array_key_exists($package_name, $this->_packages)) {
                $version_installed = $this->_packages[$package_name];
                //remove from packagist
                unset($this->_packages[$package_name]);
                $this->savePackages();
                //get package info
                $package_info = json_decode(file_get_contents("https://packagist.org/packages/$package_name.json"), true);
                $git_name = $package_info["package"]["repository"];
                $git_name = substr($git_name, strrpos($git_name, '/', strrpos($git_name, '/') - strlen($git_name) - 1) + 1);
                //get all directories
                $package_directory = $this->_dirHandler->system('Libs.Packagist.'.trim(str_replace('/', '-', strtolower($git_name))));
                $installed_versions = $package_directory->directories();
                //remove from autoload
                foreach($installed_versions as $version) {
                    if(array_key_exists($version->name(), $this->_autoload)) {
                        unset($this->_autoload[$version->name()]);
                    }
                    //remove version
                    $version->remove();
                }
                $this->saveAutoload();
                //remove remaining directory
                $package_directory->remove();
                //show packages
                $to_install = false;
                foreach($package_info["package"]["versions"] as $pack_v => $information) {
                    if((new VersionCheck($version_installed, $pack_v))->isMatch()==true) {
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
        public function autoremove($package_name, $package_version = false) {
            $package_info = json_decode(file_get_contents("https://packagist.org/packages/$package_name.json"), true);
            //get to_install
            $to_install = false;
            foreach($package_info["package"]["versions"] as $version => $information) {
                if(($package_version!==false)&&((new VersionCheck($package_version, $version))->isMatch()==true)) {
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
                        $this->remove($p);
                    }
                }
            }
        }
    }
