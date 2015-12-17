<html>
	<head>
		
	</head>
	<body>
		
	<?php echo \xTend\getCurrentApp(__DIR__)->getWowCompiler()->module("example"); ?>
	<?php 

		$app=xTend\getCurrentApp(__DIR__);
		$app->getModelHandler()->getModel()->function_call();

	 ?>

	</body>
</html>