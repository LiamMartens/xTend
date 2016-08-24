<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    /**
    * Lists the application configuration
    */
    Workbench::register('^config$', function($argv) {
        $conf = Core\App::config()->file('App.configuration.json');
        $configuration = json_decode($conf->read(), true);
        echo "\n"; foreach($configuration as $key => $value) {
            echo str_pad($key, 30).(is_bool($value) ? ($value ? 'true' : 'false') : $value)."\n";
        } echo "\n";
    }, 'config');

    /**
    * Shows the value of a config variable
    */
    Workbench::register('config ([a-zA-Z]+)$', function($argv) {
        $key=$argv[1];
        $conf = Core\App::config()->file('App.configuration.json');
        $configuration = json_decode($conf->read(), true);
        if(isset($configuration[$key])) {
            echo $configuration[$key]."\n";
        } else { die("Configuration key not found"); }
    });

    /**
    * Sets a configuration value
    */
    Workbench::register('^config ([a-zA-Z]+) (.*)$', function($argv) {
        $key=$argv[1]; $value=$argv[2];
        $conf = Core\App::config()->file('App.configuration.json');
        $configuration = json_decode($conf->read(), true);
        if(isset($configuration[$key])) {
            if(($value==='true')||($value==='false')) { $value=boolval($value); }
            elseif(is_numeric($value)) { $value=intval($value); }
            $configuration[$key] = $value;
            $conf->write(json_encode($configuration));
        } else { die("Configuration key not found"); }
    });