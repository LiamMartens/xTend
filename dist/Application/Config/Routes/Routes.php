<?php
    namespace Application;
    use Application\Core\Router;
    Router::home('My homepage');
    Router::error(0x0194, '404 - Page Not Found');
    // Router::error(404, ..) would also work


    $mail = new \PHPMailer\PHPMailer\PHPMailer();