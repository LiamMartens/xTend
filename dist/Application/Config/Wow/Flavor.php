<?php
	namespace Application;
    use \xTend\Core\Wow as Wow;
	$app=\xTend\Core\getCurrentApp(__NAMESPACE__);
	$wow = $app->getWowCompiler();
    $wow->setFlavor(Wow::HTML);
	$wow->setInternalExpressions();