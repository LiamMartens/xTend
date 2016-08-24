<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Creates a new controller which extends the Controller blueprint
    */
    Workbench::register('^new:controller ([a-zA-Z0-9\_\.]+)$', function($argv) {
        $name=$argv[1]; $class_name=$name; $dot_pos=strrpos($name, '.');
        // get and create containing directory + get classname
        if($dot_pos!==false) {
            $dir=Core\App::controllers()->directory(substr($name, 0, $dot_pos));
            if(!$dir->exists()) { $dir->create(); }
            $class_name=substr($name, $dot_pos+1);
        }
        // create controller file
        $controller=Core\App::controllers()->file($name.'.php');
        $controller->write('<?php
    namespace '.Workbench::namespace(Workbench::get('application')).';
    class '.$class_name.' extends Blueprints\\Controller {

    }'
        );
    }, 'new:controller');

    /**
    * Creates an empty controller which doesn't inherit the controller class
    */
    Workbench::register('^new:controller ([a-zA-Z0-9\_\.]+) empty$', function($argv) {
        $name=$argv[1]; $class_name=$name; $dot_pos=strrpos($name, '.');
        // get and create containing directory + get classname
        if($dot_pos!==false) {
            $dir=Core\App::controllers()->directory(substr($name, 0, $dot_pos));
            if(!$dir->exists()) { $dir->create(); }
            $class_name=substr($name, $dot_pos+1);
        }
        // create controller file
        $controller=Core\App::controllers()->file($name.'.php');
        $controller->write('<?php
    namespace '.Workbench::namespace(Workbench::get('application')).';
    class '.$class_name.' {

    }'
        );
    });

    /**
    * Creates a new controller which inherits the RespondController
    */
    Workbench::register('^new:controller ([a-zA-Z0-9\_\.]+) json$', function($argv) {
        $name=$argv[1]; $class_name=$name; $dot_pos=strrpos($name, '.');
        // get and create containing directory + get classname
        if($dot_pos!==false) {
            $dir=Core\App::controllers()->directory(substr($name, 0, $dot_pos));
            if(!$dir->exists()) { $dir->create(); }
            $class_name=substr($name, $dot_pos+1);
        }
        // create controller file
        $controller=Core\App::controllers()->file($name.'.php');
        $controller->write('<?php
    namespace '.Workbench::namespace(Workbench::get('application')).';
    class '.$class_name.' extends Blueprints\\RespondController {
        //The RespondController adds a protected method called respond
        //which you can use to return JSON data with a success parameter (boolean)
        //a status parameter with a code, hex code, status name and status message
        //and a data parameter which you can use to pass extra data
        //all info about the RespondController can be found in the documentation
    }'
        );
    });