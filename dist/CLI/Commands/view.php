<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Create a new empty view
    */
    Workbench::register('^new:view ([a-zA-Z0-9\_\.]+)$', function($argv) {
        $name=$argv[1]; $dot_pos=strrpos($name, '.');
        if($dot_pos!==false) {
            $dir=Core\App::views()->directory(substr($name, 0, $dot_pos));
            if(!$dir->exists()) {
                $dir->create();
            }
        }
        $view=Core\App::views()->file($name.'.wow.php', 2);
        if(Core\Wow::flavor()==Core\Wow::AT_SIGN) {
            $view->write('@version:1
@compile:change+version');
        } else {
            $view->write('<version value="1" />
<compile value="change+version" />');
        }
    }, 'new:view');

    /**
    * Creates a new view which extends a layout
    */
    Workbench::register('^new:view ([a-zA-Z0-9\_\.]+) ([a-zA-Z0-9\_\.]+)$', function($argv) {
        $name=$argv[1]; $dot_pos=strrpos($name, '.');
        // get and check layout
        $layout=$argv[2]; if(!Core\App::layouts()->file($layout.'.wow.php', 2)->exists()) { die('Layout does not exist'); }
        if($dot_pos!==false) {
            $dir=Core\App::views()->directory(substr($name, 0, $dot_pos));
            if(!$dir->exists()) {
                $dir->create();
            }
        }
        $view=Core\App::views()->file($name.'.wow.php', 2);
        if(Core\Wow::flavor()==Core\Wow::AT_SIGN) {
            $view->write('@version:1
@layout:'.$layout.'
@compile:change+version');
        } else {
            $view->write('<version value="1" />
<layout value="'.$layout.'" />
<compile value="change+version" />');
        }
    });