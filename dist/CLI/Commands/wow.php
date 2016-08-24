<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Sets the wow flavor
    */
    Workbench::register('wow:flavor (HTML|AT_SIGN|COMBINED)', function($argv) {
        $file = Core\App::config()->file('Wow.Flavor.php');
        $file->write('<?php
    /**
    * Sets the current Wow flavor
    * and initializes the Wow engine
    */
    namespace '.Workbench::namespace(Workbench::get('application')).';
    use '.Workbench::namespace(Workbench::get('application')).'\Core\Wow;
    Wow::flavor(Wow::'.$argv[1].');
    Wow::start();'
        );
    }, 'wow:flavor');