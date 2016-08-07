<?php
    namespace Application\Core;
    //include ex classes
    FileManager::include(__DIR__.'/Exception/CryptoException.php');
    FileManager::include(__DIR__.'/Exception/CryptoTestFailedException.php');
    FileManager::include(__DIR__.'/Exception/CannotPerformOperationException.php');
    FileManager::include(__DIR__.'/Exception/InvalidCiphertextException.php');
    //include handler
    FileManager::include(__DIR__.'/ExceptionHandler.php');
    //include crypto self
    FileManager::include(__DIR__.'/Crypto.php');
    //set ex handler
    $crypto_exception_handler_object_dont_touch_me = new \Defuse\Crypto\ExceptionHandler;