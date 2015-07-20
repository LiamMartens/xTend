<?php
if (version_compare(PHP_VERSION, '5.3') < 0) {
    die('Requires PHP 5.3 or above');
}

if ( ! class_exists('scssc')) {
    include_once __DIR__ . '/scss/Colors.php';
    include_once __DIR__ . '/scss/Compiler.php';
    include_once __DIR__ . '/scss/Formatter.php';
    include_once __DIR__ . '/scss/Formatter/Compact.php';
    include_once __DIR__ . '/scss/Formatter/Compressed.php';
    include_once __DIR__ . '/scss/Formatter/Crunched.php';
    include_once __DIR__ . '/scss/Formatter/Expanded.php';
    include_once __DIR__ . '/scss/Formatter/Nested.php';
    include_once __DIR__ . '/scss/Parser.php';
    include_once __DIR__ . '/scss/Version.php';
    include_once __DIR__ . '/scss/Server.php';
}
