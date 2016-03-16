<?php
    namespace Defuse\Crypto;
    use \xTend\ClassManager as ClassManager;
    class CryptoLoader
    {
        public static function load() {
            //include ex classes
            ClassManager::includeClass("Defuse\\Crypto\\Exception\\CryptoException", __DIR__."/Exception/CryptoException.php");
            ClassManager::includeClass("Defuse\\Crypto\\Exception\\CryptoTestFailedException", __DIR__."/Exception/CryptoTestFailedException.php");
            ClassManager::includeClass("Defuse\\Crypto\\Exception\\CannotPerformOperationException", __DIR__."/Exception/CannotPerformOperationException.php");
            ClassManager::includeClass("Defuse\\Crypto\\Exception\\InvalidCiphertextException", __DIR__."/Exception/InvalidCiphertextException.php");
            //include handler
            ClassManager::includeClass("Defuse\\Crypto\\ExceptionHandler", __DIR__."/ExceptionHandler.php");
            //include crypto self
            ClassManager::includeClass("Defuse\\Crypto\\Crypto", __DIR__."/Crypto.php");
            //set ex handler
            $crypto_exception_handler_object_dont_touch_me = new ExceptionHandler;
        }
    }