<?php
    namespace Defuse\Crypto;
    use \xTend\Core\ClassManager as ClassManager;
    class CryptoLoader
    {
        public static function load() {
            //include ex classes
            ClassManager::includeClass(__DIR__."/Exception/CryptoException.php");
            ClassManager::includeClass(__DIR__."/Exception/CryptoTestFailedException.php");
            ClassManager::includeClass(__DIR__."/Exception/CannotPerformOperationException.php");
            ClassManager::includeClass(__DIR__."/Exception/InvalidCiphertextException.php");
            //include handler
            ClassManager::includeClass(__DIR__."/ExceptionHandler.php");
            //include crypto self
            ClassManager::includeClass(__DIR__."/Crypto.php");
            //set ex handler
            $crypto_exception_handler_object_dont_touch_me = new ExceptionHandler;
        }
    }
