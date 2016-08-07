<?php
    namespace Application\Core;

    /**
    * The Wow class handles the templating
    * and layouting engine
    */
    class Wow {
        /** @var string regex for php variable and function names */
        const PHP_NAME_RX = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
        /** @var integer html flavor code */
        const HTML = 0;
        /** @var integer combined flavor code */
        const COMBINED = 1;
        /** @var integer at_sign flavor code */
        const AT_SIGN = 2;

        /** @var string Contains the regex for extracting the view version */
        private static $_rx_version;
        /** @var string Contains the regex for extracting the name of the layout to extend */
        private static $_rx_layout;
        /** @var string Contains the regex for extracting the compile flag */
        private static $_rx_flag;
        /** @var string Contains the regex for detecing sections */
        private static $_rx_section;
        /** @var string Contains the regex for getting the section name */
        private static $_rx_section_name;
        /** @var string Contains the regex for extracting the sections */
        private static $_rx_section_extract;
        /** @var string Contains the regex for detecing modules */
        private static $_rx_module;
        /** @var string Contains the regex for extracting  modules */
        private static $_rx_module_extract;

        /** @var integer Contains the current Wow flavor */
        private static $_flavor=Wow::COMBINED;
        /** @var array Contains the current expressions */
        private static $_expressions=[];

        /**
        * Sets or gets the flavor
        *
        * @param int:optional $fl
        *
        * @return int
        */
        public static function flavor($fl=null) {
            if(self::$_flavor!==null) {
                self::$_flavor=$fl;
            }
            return self::$_flavor;
        }

        /**
        * Creates a regex
        *
        * @param string $pattern
        * @param string $flags
        *
        * @return string
        */
        public static function rx($pattern, $flags) {
            return '/'.$pattern.'/'.$flags;
        }

        public static function start() {
            if(self::$_flavor==Wow::HTML) {
                self::$_rx_version = self::rx("\<version\s+value=\"([0-9\.]+)\"\s*\/?\>", "i");
                self::$_rx_layout = self::rx("\<layout\s+value=\"([\w\-\_\.]+)\"\s*\/?\>", "i");
                self::$_rx_flag = self::rx("\<compile\s+value=\"(change\+version|always|version|never|change)\"\s*\/?\>", "i");
                self::$_rx_section = self::rx("(\<section\s+name=\"[\w\-\_]+\"\s*\/?\>)", "i");
                self::$_rx_section_name = self::rx("\<section\s+name=\"([\w\-\_]+)\"\s*\/?\>", "i");
                self::$_rx_section_extract = self::rx("\<section\s+name=\"%s\"\s*\>(.*?)\<\/section\>", "si");
                self::$_rx_module = self::rx("\<module\s+name=\"([\w\-\_\.]+)\"\s*\/?\>", "i");
                self::$_rx_module_extract = self::rx("(\<module\s+name=\"[\w\-\_\.]+\"\s*\/?\>)", "i");
            } elseif(self::$_flavor==Wow::AT_SIGN) {
                self::$_rx_version = self::rx("@version:([0-9\.]+)", "i");
                self::$_rx_layout = self::rx("@layout:([\w\-\_\.]+)", "i");
                self::$_rx_flag = self::rx("@compile:(change\+version|always|version|never|change)", "i");
                self::$_rx_section = self::rx("(@section:[\w\-\_]+)", "i");
                self::$_rx_section_name = self::rx("@section:([\w\-\_]+)", "i");
                self::$_rx_section_extract = self::rx("@startsection:%s(.*?)@endsection", "si");
                self::$_rx_module = self::rx("@module:([\w\-\_\.]+)", "i");
                self::$_rx_module_extract = self::rx("(@module:[\w\-\_\.]+)", "i");
            } else {
                self::$_rx_version = self::rx("(?:\<version\s+value=\"([0-9\.]+)\"\s*\/?\>)|(?:@version:([0-9\.]+))", "i");
                self::$_rx_layout = self::rx("(?:\<layout\s+value=\"([\w\-\_\.]+)\"\s*\/?\>)|(?:@layout:([\w\-\_\.]+))", "i");
                self::$_rx_flag = self::rx("(?:\<compile\s+value=\"(change\+version|always|version|never|change)\"\s*\/?\>)|(?:@compile:(change\+version|always|version|never|change))", "i");
                self::$_rx_section = self::rx("(?:(\<section\s+name=\"[\w\-\_]+\"\s*\/?\>))|(?:@section:[\w\-\_]+)", "i");
                self::$_rx_section_name = self::rx("(?:\<section\s+name=\"([\w\-\_]+)\"\s*\/?\>)|(?:@section:([\w\-\_]+))", "i");
                self::$_rx_section_extract = self::rx("(?:\<section\s+name=\"%s\"\s*\>(.*?)\<\/section\>)|(?:@startsection:%s(.*?)@endsection)", "si");
                self::$_rx_module = self::rx("(?:\<module\s+name=\"([\w\-\_\.]+)\"\s*\/?\>)|(?:@module:([\w\-\_\.]+))", "i");
                self::$_rx_module_extract = self::rx("(?:(\<module\s+name=\"[\w\-\_\.]+\"\s*\/?\>))|(?:(@module:[\w\-\_\.]+))", "i");
            }
        }

        /**
        * Register a new expression
        *
        * @param string $rx
        * @param string $replacement
        */
        public static function register($rx, $replacement) {
            self::$_expressions[$rx]=$replacement;
        }

        /**
        * Checks the version of the view out of content
        *
        * @param string reference $content
        *
        * @return float|boolean
        */
        private static function _version(&$content) {
            //must be at the beginning of a line
            $rx_matches=[]; preg_match(self::$_rx_version, $content, $rx_matches);
            if(isset($rx_matches[1]))
                return floatval($rx_matches[1]);
            return false;
        }

        /**
        * Gets the layout name out of a view's content
        *
        * @param string reference $content
        *
        * @return string
        */
        private static function _layout(&$content) {
            $rx_matches=[]; preg_match(self::$_rx_layout, $content, $rx_matches);
            if(isset($rx_matches[1]))
                return $rx_matches[1];
            return false;
        }

        /**
        * Gets the compile flag out of a view's content
        *
        * @param string reference $content
        *
        * @return string|boolean
        */
        private static function _flag(&$content) {
            //compile flags -> always (always compile), version (compile on version change to higher one),
            //never (never compile), change (compile on content change) -> version stays the same so
            //beware for data loss in compiled views
            //change+version -> only compile when the version changes and the content changes, this prevents
            //data loss in the compiled outputs as you do need to manually change the version number
            $rx_matches=[]; preg_match(self::$_rx_flag, $content, $rx_matches);
            if(isset($rx_matches[1]))
                return $rx_matches[1];
            return false;
        }

        /**
        * Checks whether the view was changed
        *
        * @param string $path
        * @param string reference $view_content
        * @param string|boolean $layout
        *
        * @return boolean
        */
        private static function changed($path, &$view_content, $layout=false) {
            //get last modification time
            $time_last_mod=filemtime($path);
            //get last compile times
            $last_compile = $path->meta('last_compile');
            //if last compile hasn't been saved -> return true
            if($last_compile==false) return true;
            //if the last compile time is smaller than the last compile time also return true
            if(floatval($last_compile)<$time_last_mod) return true;
            //check for all the modules
            $modules=[]; preg_match_all(self::$_rx_module, $view_content, $modules);
            if(isset($modules[1])) {
                foreach ($modules[1] as $mod_name) {
                    $file_path=App::modules()->file($mod_name.'.wow.php', 2);
                    if($file_path->exists()) {
                        //module exists -> check the change time
                        if(filemtime($file_path)>floatval($last_compile)) return true; } } }
            //check layout compile time
            if($layout!==false) {
                //a layout was passed and no value was returned yet -> check the layout change and the view compile time
                $time_layout_last_mod = filemtime($layout);
                if($time_layout_last_mod>floatval($last_compile)) return true;
            }
            //if all fails return false
            return false;
        }

        /**
        * Sets the last_compile time of a path
        *
        * @param File $path
        */
        private static function update($path) {
            $path->meta('last_compile', time());
        }

        /**
        * Checks whether a part has been fully compiled and contains more expressions
        *
        * @param string $content
        *
        * @return boolean
        */
        private static function fullyCompiled($content) {
            if(preg_match(self::$_rx_module, $content)==1) { return false; }
            foreach(self::$_expressions as $rx => $repl) {
                if(preg_match($rx, $content)==1) { return false; }
            }
            return true;
        }

        /**
        * Compiles a section with the registered expressions (non internal)
        *
        * @param string $content
        * @param string|boolean $modules_dir
        *
        * @return string
        */
        private static function raw($content, $modules_dir = false) {
            foreach (self::$_expressions as $rx => $repl) {
                $content = preg_replace($rx, $repl, $content);
            }
            if(!self::fullyCompiled($content)) {
                return self::compile($content, $modules_dir);
            }
            return $content;
        }

        /**
        * Compiles a view content
        *
        * @param string $content
        * @param string|boolean $modules_dir
        *
        * @return string
        */
        private function compile($content, $modules_dir = false) {
            //remove @version, @compile, @layout and @section flags as these
            //should not be repaced by anything, they should be ignored
            $content = preg_replace(self::_rx_version, '', $content);
            $content = preg_replace(self::_rx_layout, '', $content);
            $content = preg_replace(self::_rx_flag, '', $content);
            $content = preg_replace(self::_rx_section, '', $content);
            //split into modules if any
            $final_content='';
            $mod_split = preg_split(self::$_rx_module_extract, $content, NULL, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($mod_split as $part) {
                $module_match=[]; $module_matched = preg_match(self::$_rx_module, $part, $module_match);
                if(!$module_matched) {
                    //it is not a module
                    $part=self::raw($part, $modules_dir);
                } else {
                    //get module contents
                    $mod_name=$module_match[1];
                    $mod_path=($modules_dir===false) ?
                                    (App::modules()->file($mod_name.'.wow.php', 2)) :
                                    ($modules_dir->file($mod_name.'.wow.php', 2));
                    if($mod_path->exists()) {
                        $part=self::compile($mod_path->read(), $modules_dir); } }
                $final_content.=$part; }
            $content=$final_content;
            return $final_content;
        }

        /**
        * Compiles a layout
        *
        * @param string $layout_c
        * @param string|boolean $modules_dir
        *
        * @return array
        */
        private static function layout($layout_c, $modules_dir = false) {
            //split the layout into sections
            $layout_c=self::raw($layout_c, $modules_dir);
            $split = preg_split(self::$_rx_section, $layout_c, NULL, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($split as &$part) {
                if(!preg_match(self::$_rx_section, $part))
                    self::compile($part, $modules_dir);
            }
            //returns array of compiled parts and the section parts
            return $split;
        }

        /**
        * Starts a view compilation
        *
        * @param string $file
        * @param string|boolean $layout_dir
        * @param string|boolean $modules_dir
        *
        * @return string
        */
        public function view($file, $layout_dir = false, $modules_dir = false) {
            //file hash
            $file_hash = hash('sha256', $file);
            //get file name 'name'
            $file_name=substr($file, strrpos($file, "/")+1); $file_name=substr($file_name, 0, strpos($file_name, "."));
            //get view content
            $view_c = $file->read();
            //get view flags
            $version=self::_version($view_c);
            $layout=self::_layout($view_c);
            $layout_path=false;
            $flag=self::_flag($view_c);
            //check for layout existance -> if it doesnt exist, ignore the layout, thus set it to false
            if($layout!==false) {
                $layout_path=($layout_dir===false) ?
                                    (App::layouts()->file($layout.'.wow.php', 2)) :
                                    ($layout_dir->file($layout.'.wow.php', 2));
                if(!$layout_path->exists()) { $layout=false; $layout_path=false; } }
            //get last compiled version of this view file -> sorting works descending thus most recent versions are first
            $is_new_version = true; $one_found=false;
            $compiled_views = App::viewOutput()->files(); rsort($compiled_views);
            //check for the current version in the array
            foreach ($compiled_views as $cv) {
                $sl_pos = strrpos($cv, '/');
                $pos=strpos($cv, '.v');
                //get version number
                $v_num = floatval(trim(substr($cv, $pos+2), '.php'));
                //get actual view name -> hash("sha256") of the view path
                $v_hash = substr($cv, $sl_pos+1, $pos-$sl_pos-1);
                //check for hash compliance
                if($v_hash==$file_hash) {
                    $one_found=true; if($v_num>=$version) {
                        $is_new_version=false; break; } } }
            //get whether the view has changed (either the view itself or the layout)
            $has_changed=self::changed($file, $view_c, $layout_path);
            //check whether view needs to be compiled
            $compile_view=false;
            if($flag!==false) {
                //set to lowercase
                $flag=strtolower($flag);
                //check flag option
                if(($flag=='always')||
                    (($flag!='never')&&(!$one_found))||
                    (($flag=='version')&&($is_new_version))||
                    (($flag=='change')&&($has_changed))||
                    (($flag=='change+version')&&($has_changed)&&($is_new_version))) {
                    $compile_view=true;
                }
            } else {$compile_view=true;}
            //view has to be compiled
            if($compile_view) {
                $compiled_string='';
                if($layout!==false) {
                    //compile using a layout
                    $layout_parts=self::layout($layout_path->read(), $modules_dir);
                    foreach ($layout_parts as $part) {
                        //check for section request
                        $section_match=[]; $is_section = preg_match(self::$_rx_section_name, $part, $section_match);
                        if($is_section==1) {
                            $section_name=$section_match[1];
                            //got the section name, now take the section content out of the view content
                            $rx = sprintf(self::$_rx_section_extract, $section_name, $section_name);
                            $rx_matches=[]; preg_match($rx, $view_c, $rx_matches);
                            if(isset($rx_matches[1]))
                                $compiled_string.=self::compile($rx_matches[1], $modules_dir);
                        } else { $compiled_string.=$part; }
                    }
                } else { $compiled_string=self::compile($file->read(), $modules_dir); }
                //add namespace to compiled_string
                $compiled_string="<?php namespace ".App::namespace()."; ?>".$compiled_string;
                //write view output
                App::viewOutput()->file("$file_hash.v$version.php", 2)->write($compiled_string);
                //update meta file
                self::update($file);
            }
            //return compiled view filename
            return (App::viewOutput()->file("$file_hash.v$version.php", 2));
        }
    }
