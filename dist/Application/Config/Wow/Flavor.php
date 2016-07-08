<?php
    /**
    * Sets the current Wow flavor
    * and initializes the Wow engine
    */
    namespace Application;
    use \xTend\Core\Wow as Wow;
    $app=\xTend\Core\getCurrentApp(__NAMESPACE__);
    $wow = $app->getWowCompiler();
    $wow->setFlavor(Wow::COMBINED);
    $wow->setInternalExpressions();
