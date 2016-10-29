<?php
    namespace Application;
    use Application\Core\ORM;
    use Application\Core\App;

/*
    //db.yml configuration (environment specific)

    $configuration = Spyc::YAMLLoad(__DIR__.'/db.yml');
    $env = App::environment();
    if(isset($configuration['environments'][$env])) {
        $db = $configuration['environments'][$env];
        $adapter = $db['adapter'];
        $host = $db['host'];
        $name = $db['name'];
        $user = $db['user'];
        $pass = $db['pass'];
        $port = $db['port'];
        $charset = $db['charset'];

        if($db['adapter']!='sqlite') {
            ORM::configure("$adapter:host=$host;dbname=$name;port=$port;charset=$charset");
            ORM::configure('username', $user);
            ORM::configure('password', $pass);
        } else {
            ORM::configure("sqlite:$name");
        }
    }

    //manual configuration

    https://idiorm.readthedocs.io
    https://paris.readthedocs.io

    ORM::configure('sqlite:./example.db');

    ORM::configure('mysql:host=localhost;dbname=my_database');
    ORM::configure('username', 'database_user');
    ORM::configure('password', 'top_secret');
*/