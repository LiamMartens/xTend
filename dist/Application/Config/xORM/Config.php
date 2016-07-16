<?php
    namespace Application;
    use \xTend\Core\xORM;
    $app = \xTend\Core\getCurrentApp(__NAMESPACE__);
    $orm = $app->orm();
    /*
    $orm->configure(xORM::DRIVER_MYSQL, '127.0.0.1', [
        'user' => 'root',
        'password' => 'pass
    ]);

    $orm->configure(xORM::DRIVER_SQLITE, './db.sqlite');
    */