<?php
	namespace Application;
	$app=\xTend\Core\getCurrentApp(__NAMESPACE__);
	$wow = $app->getWowCompiler();
	//register expressions
	//insert echo
	$wow->registerExpression(
		$wow->rx("(@)(\()(\$.+?)(\))", "i"),
		"<?php echo $$4; ?>"
	);

	//
	//	<php>
	//
	//		echo 'ok';
	//
	//	</php>
	//
	$wow->registerExpression(
		$wow->rx("\<php\>(.+?)\<\/php\>", "is"),
		"<?php $1 ?>"
	);

	//
	//	<if>
	//		<condition>1==1</condition>
	//
	//		echo "1 is 1";
	//
	//	</if>
	//
	$wow->registerExpression(
		$wow->rx("\<if\>\s*\<condition\>(.+?)\<\/condition\>(.+?)\<\/if\>", "is"),
		"<?php if($1) { ?>$2<?php } ?>"
	);

	//
	//	Can only be used inside an if
	//	<elseif>
	//		<condition>1==1</condition>
	//
	//		echo "1 is 1";
	//
	//	</elseif>
	//
	$wow->registerExpression(
		$wow->rx("\<elseif\>\s*\<condition\>(.+?)\<\/condition\>(.+?)\<\/elseif\>", "is"),
		"<?php } elseif($1) { ?>$2"
	);

	//
	//	Can only be used inside an if
	//	<else>
	//
	//		echo "1 is 1";
	//
	//	</else>
	//
	$wow->registerExpression(
		$wow->rx("\<else\>(.*?)\<\/else\>", "is"),
		"<?php } else { ?>$1"
	);


	//
	//	<for>
	//		<loop>$i=0;...$i++</loop>
	//		<li>..</li>
	//	</for>
	//
	$wow->registerExpression(
		$wow->rx("\<for\>\s*\<loop\>(.+?)\<\/loop\>(.+?)\<\/for\>", "is"),
		"<?php for($1) { ?>$2<?php } ?>"
	);

	//
	//	<foreach>
	//		<loop>$i in $b</loop>
	//		<li>..</li>
	//	</foreach>
	//
	$wow->registerExpression(
		$wow->rx("\<foreach\>\s*\<loop\>(.+?)\<\/loop\>(.+?)\<\/foreach\>", "is"),
		"<?php foreach($1) { ?>$2<?php } ?>"
	);

	//
	//	<while>
	//		<condition>$i < 10</condition>
	//		<li>..</li>
	//	</while>
	//
	$wow->registerExpression(
		$wow->rx("\<while\>\s*\<condition\>(.+?)\<\/condition\>(.+?)\<\/while\>", "is"),
		"<?php while($1) { ?>$2<?php } ?>"
	);

	//
	//	<css href="/css/style.css"/>
	//
	$wow->registerExpression(
		$wow->rx("\<css\s+href=\"(.+?)\"\s*\/\>","i"),
		'<link rel="stylesheet" href="$1" type="text/css">'
	);
	//
	//	<css>/css/style.css</css>
	//
	$wow->registerExpression(
		$wow->rx("\<css\>(.+?)\<\/css\>","is"),
		'<link rel="stylesheet" href="$1" type="text/css">'
	);

	//
	//	. file notation can be used but is not necessary
	//	<css embed="css.style.css"/>
	//
	$wow->registerExpression(
		$wow->rx("\<css\s+embed=\"(.+?)\"\s*\/\>","i"),
		'<style type="text/css"><?php echo $app->getFileHandler()->public(\'$1\')->read(); ?></style>'
	);
	//
	//	. file notation can be used but is not necessary
	//	<css embed>css.style.css</css>
	//
	$wow->registerExpression(
		$wow->rx("\<css\s+embed\s*\>(.+?)\<\/css\>","is"),
		'<style type="text/css"><?php echo $app->getFileHandler()->public(\'$1\')->read(); ?></style>'
	);

	//
	//	<css external-embed="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
	//
	$wow->registerExpression(
		$wow->rx("\<css\s+external-embed=\"(.+?)\"\s*\/\>","i"),
		'<style type="text/css"><?php echo file_get_contents(\'$1\'); ?></style>'
	);
	//
	//	<css external-embed>...</css>
	//
	$wow->registerExpression(
		$wow->rx("\<css\s+external-embed\s*\>(.+?)\<\/css\>","i"),
		'<style type="text/css"><?php echo file_get_contents(\'$1\'); ?></style>'
	);

	//
	//	<js src="js/file.js"/>
	//
	$wow->registerExpression(
		$wow->rx("\<js\s+src=\"(.+?)\"\s*\/\>","i"),
		'<script type="text/javascript" src="$1"></script>'
	);
	//
	//	<js>js/file.js</js>
	//
	$wow->registerExpression(
		$wow->rx("\<js\>(.+?)\<\/js\>","is"),
		'<script type="text/javascript" src="$1"></script>'
	);

	//
	//	. file notation can be used but is not necessary
	//	<js embed="js/file.js"/>
	//
	$wow->registerExpression(
		$wow->rx("\<js\s+embed=\"(.+?)\"\s*\/\>","i"),
		'<script type="text/javascript"><?php echo $app->getFileHandler()->public(\'$1\')->read(); ?></script>'
	);
	$wow->registerExpression(
		$wow->rx("\<js\s+embed\s*\>(.+?)\<\/js\>","is"),
		'<script type="text/javascript"><?php echo $app->getFileHandler()->public(\'$1\')->read(); ?></script>'
	);

	//
	//	<js external-embed="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"/>
	//
	$wow->registerExpression(
		$wow->rx("\<js\s+external-embed=\"(.+?)\"\s*\/\>","i"),
		'<script type="text/javascript"><?php echo file_get_contents(\'$1\'); ?></script>'
	);
	//
	//	<js external-embed>https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js</js>
	//
	$wow->registerExpression(
		$wow->rx("\<js\s+external-embed\s*\>(.+?)\<\/js\>","is"),
		'<script type="text/javascript"><?php echo file_get_contents(\'$1\'); ?></script>'
	);

	//
	//	<url />
	//
	$wow->registerExpression(
		$wow->rx("\<url\s*\/\>","i"),
		"<?php echo \$app->getUrl(); ?>"
	);

	$wow->registerExpression(
		$wow->rx("\<app\>(.+?)\<\/app\>", "i"),
		"<?php echo \$app->$1; ?>"
	);


	//
	//	<controller name="..." (optional)>COMMAND</controller>
	//
	//
	$wow->registerExpression(
		$wow->rx("\<controller\>(.+?)\<\/controller\>","i"),
		"<?php echo \$app->getControllerHandler()->getController()->$1; ?>"
	);
	$wow->registerExpression(
		$wow->rx("\<controller name=\"(.+?)\"\>(.+?)\<\/controller\>","i"),
		"<?php echo \$app->getControllerHandler()->getController('$1')->$2; ?>"
	);
