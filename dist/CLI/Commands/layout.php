<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Creates a new basic layout
    */
    Workbench::register('^new:layout ([a-zA-Z0-9\_\.]+)$', function($argv) {
        $name=$argv[1]; $dot_pos=strrpos($name, '.');
        if($dot_pos!==false) {
            $dir=Core\App::layouts()->directory(substr($name, 0, $dot_pos));
            if(!$dir->exists()) {
                $dir->create();
            }
        }
        $layout=Core\App::layouts()->file($name.'.wow.php', 2);
        if(Core\Wow::flavor()==Core\Wow::AT_SIGN) {
            $layout->write('<!DOCTYPE html>
<html>
    <head>
        @section:head
    </head>
    <body>
        @section:body
    </body>
</html>'
            );
        } else {
            $layout->write('<!DOCTYPE html>
<html>
    <head>
        <section name="head" />
    </head>
    <body>
        <section name="body" />
    </body>
</html>'
            );
        }
    }, 'new:layout');