<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    Workbench::register('^init$', function($argv) {
        $configuration_file = Core\App::config()->file('Sessions.sessions.json');
        $configuration = json_decode($configuration_file->read(), true);
        // set values
        $configuration['sessionName'] = sha1(random_bytes(8));
        $configuration['initiatedKey'] = sha1(random_bytes(8));
        $configuration['userAgentKey'] = sha1(random_bytes(8));
        $configuration['salt'] = sha1(random_bytes(8));
        $configuration['userSessionsKey'] = sha1(random_bytes(8));
        $configuration['userCookiesKey'] = sha1(random_bytes(8));
        // write configuration
        $configuration_file->write(json_encode($configuration));
    }, 'init');

    Workbench::register('^init show$', function($argv) {
        $configuration_file = Core\App::config()->file('Sessions.sessions.json');
        $configuration = json_decode($configuration_file->read(), true);
        echo "\n"; foreach($configuration as $key => $value) {
            echo str_pad($key, 30).$value."\n";
        } echo "\n";
    }, 'init show');