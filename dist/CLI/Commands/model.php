<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Creates a new model which inherits the initiated blueprint Model
    */
    Workbench::register('^new:model ([a-zA-Z0-9\_\.]+)$', function($argv) {
        $name=$argv[1]; $class_name=$name; $dot_pos=strrpos($name, '.');
        // get and create containing directory + get classname
        if($dot_pos!==false) {
            $dir=Core\App::models()->directory(substr($name, 0, $dot_pos));
            if(!$dir->exists()) { $dir->create(); }
            $class_name=substr($name, $dot_pos+1);
        }
        // create controller file
        $controller=Core\App::models()->file($name.'.php');
        $controller->write('<?php
    namespace '.Workbench::namespace(Workbench::get('application')).';
    class '.$class_name.' extends Blueprints\\Model {
        protected static $_table = \''.str_replace('.', '_', $name).'\';
        protected static $_id_column = \'id\';
    }'
        );
    }, 'new:model');

    /**
    * Creates a new model which doesn't inherit the blueprint
    */
    Workbench::register('^new:model ([a-zA-Z0-9\_\.]+) empty$', function($argv) {
        $name=$argv[1]; $class_name=$name; $dot_pos=strrpos($name, '.');
        // get and create containing directory + get classname
        if($dot_pos!==false) {
            $dir=Core\App::models()->directory(substr($name, 0, $dot_pos));
            if(!$dir->exists()) { $dir->create(); }
            $class_name=substr($name, $dot_pos+1);
        }
        // create controller file
        $controller=Core\App::models()->file($name.'.php');
        $controller->write('<?php
    namespace '.Workbench::namespace(Workbench::get('application')).';
    class '.$class_name.' {
        
    }'
        );
    });