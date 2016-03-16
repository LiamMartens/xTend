<?php
	namespace xTend\Application;
	$app=\xTend\getCurrentApp(__NAMESPACE__);
	$wow = $app->getWowCompiler();
	//register expressions
	//insert echo
	$wow->registerExpression(
		$wow->rx("(@)(\()(\$.+?)(\))", "i"),
		"<?php echo $$4; ?>"
	);
	//insert PHP code
	$wow->registerExpression(
		$wow->rx("(@)(\(\{)(.+?)(\}\))", "is"),
		"<?php $3 ?>"
	);
	//if
	$wow->registerExpression(
		$wow->rx("(@if\()(.*?)(\))", "i"),
		"<?php if($2) { ?>"
	);
	//elseif
	$wow->registerExpression(
		$wow->rx("(@elseif\()(.*?)(\))", "i"),
		"<?php } elseif($2) { ?>"
	);
	//else
	$wow->registerExpression(
		$wow->rx("(@else)", "i"),
		"<?php } else { ?>"
	);
	//for
	$wow->registerExpression(
		$wow->rx("(@for\()(.*?)(;)(.*?)(;)(.*?)(\))", "i"),
		"<?php for($2$3$4$5$6) { ?>"
	);
	//foreach
	$wow->registerExpression(
		$wow->rx("(@foreach\()(.*?)( )(as)( )(.*?)(\))", "i"),
		"<?php foreach($2 as $6) { ?>"
	);
	//while
	$wow->registerExpression(
		$wow->rx("(@while\()(.*?)(\))", "i"),
		"<?php while($2) { ?>"
	);
	//end }
	$wow->registerExpression(
		$wow->rx("(@end)","i"),
		"<?php } ?>"
	);
	//internal css
	$wow->registerExpression(
		$wow->rx("(@css:)(.*?)","i"),
		'<link rel="stylesheet" href="<?php echo \$app->getUrl()."/$2"; ?>" type="text/css">'
	);
	//external css
	$wow->registerExpression(
		$wow->rx("(@csse:)(.*?)","i"),
		'<link rel="stylesheet" href="$2" type="text/css">'
	);
	//internal script
	$wow->registerExpression(
		$wow->rx("(@script:)(.*?)","i"),
		'<script src="<?php echo \$app->getUrl()."/$2"; ?>" type="text/javascript"></script>'
	);
	//external script
	$wow->registerExpression(
		$wow->rx("(@scripte:)(.*?)","i"),
		'<script src="$2" type="text/javascript"></script>'
	);
	//app lang
	$wow->registerExpression(
		$wow->rx("(@lang)","i"),
		"<?php echo \$app->getLanguage(); ?>"
	);
	//app charset
	$wow->registerExpression(
		$wow->rx("(@charset)","i"),
		"<?php echo \$app->getCharset(); ?>"
	);
	//app description
	$wow->registerExpression(
		$wow->rx("(@description)","i"),
		"<?php echo \$app->getDescription(); ?>"
	);
	//app keywords
	$wow->registerExpression(
		$wow->rx("(@keywords)","i"),
		"<?php echo \$app->getKeywords(); ?>"
	);
	//app author
	$wow->registerExpression(
		$wow->rx("(@author)","i"),
		"<?php echo \$app->getAuthor(); ?>"
	);
	//app url
	$wow->registerExpression(
		$wow->rx("(@url)","i"),
		"<?php echo \$app->getUrl(); ?>"
	);
	//app title
	$wow->registerExpression(
		$wow->rx("(@title:)(.*?)","i"),
		"<title>$2</title>"
	);
	//get current app
	$wow->registerExpression(
		$wow->rx("(@app:)(\{)(.+?)(\})","i"),
		"<?php echo \$app->$3; ?>"
	);
	$wow->registerExpression(
		$wow->rx("(@controller:)([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\(\))?)","i"),
		"<?php echo \$app->getControllerHandler()->getController()->$2; ?>"
	);
	$wow->registerExpression(
		$wow->rx("(@controller:)([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*):([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\(\))?)","i"),
		"<?php echo \$app->getControllerHandler()->getController(\"$2\")->$3; ?>"
	);
    $wow->registerExpression(
        $wow->rx("(@nohref)", "i"),
        "javascript:void(0)"
    );