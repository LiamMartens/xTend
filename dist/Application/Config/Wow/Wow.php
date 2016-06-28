<?php
	namespace Application;
	use \xTend\Core\Wow as Wow;
	$app=\xTend\Core\getCurrentApp(__NAMESPACE__);
	$wow = $app->getWowCompiler();

	if($wow->getFlavor()===Wow::HTML) {
		//
		//	<echo>$username</echo>
		//
		$wow->registerExpression(
			$wow->rx("\<echo\>(.+?)\<\/echo\>", "i"),
			"<?php echo $1; ?>"
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
			$wow->rx("\<url inject\s*\/\>","i"),
			"\$app->getUrl()"
		);

		//
		//	<app>getDevelopmentStatus()</app>
		//
		$wow->registerExpression(
			$wow->rx("\<app\>(.+?)\<\/app\>", "i"),
			"<?php echo \$app->$1; ?>"
		);
		$wow->registerExpression(
			$wow->rx("\<app inject\>(.+?)\<\/app\>", "i"),
			"\$app->$1"
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
		$wow->registerExpression(
			$wow->rx("\<controller inject\>(.+?)\<\/controller\>","i"),
			"\$app->getControllerHandler()->getController()->$1"
		);
		$wow->registerExpression(
			$wow->rx("\<controller inject name=\"(.+?)\"\>(.+?)\<\/controller\>","i"),
			"\$app->getControllerHandler()->getController('$1')->$2"
		);

		//
		//	<formtoken name="..." />
		//
		$wow->registerExpression(
			$wow->rx("\<formtoken\s+name=\"(.+?)\"\s*\/\>", "i"),
			'<input type="hidden" name="token-$1" value="<?php echo $app->getFormTokenHandler()->generate("$1"); ?>" />'
		);
	} elseif($wow->getFlavor()===Wow::AT_SIGN) {
		//
		//	@echo:$username
		//
		$wow->registerExpression(
			$wow->rx("@echo:(.+)", "i"),
			"<?php echo $1; ?>"
		);

		//
		//	@php:
		//
		//		echo 'ok';
		//
		//	@endphp
		//
		$wow->registerExpression(
			$wow->rx("@php:(.+?)@endphp", "is"),
			"<?php $1 ?>"
		);

		//
		//	Used to end statements such as if, foreach, ...
		//	@end
		//
		$wow->registerExpression(
			$wow->rx("@end", "is"),
			"<?php } ?>"
		);

		//
		//	@if:true
		//		<p>ok</p>
		//	@end
		//
		$wow->registerExpression(
			$wow->rx("@if:(.+)", "i"),
			"<?php if($1) { ?>"
		);

		//
		//	Can only be used after an @if before the @end
		//	@elseif:true
		//		<p>ok</p>
		//
		$wow->registerExpression(
			$wow->rx("@elseif:(.+)", "i"),
			"<?php } elseif($1) { ?>"
		);

		//
		//	Can only be used after an @if before the @end
		//	@else
		//		<p>ok</p>
		//
		$wow->registerExpression(
			$wow->rx("@else", "i"),
			"<?php } else { ?>"
		);

		//
		//	@for:$i=0;$i<10;$i++
		//		<li></li>
		//	@end
		//
		$wow->registerExpression(
			$wow->rx("@for:(.+)", "i"),
			"<?php for($1) { ?>"
		);

		//
		//	@foreach:$items as $item
		//		<li></li>
		//	@end
		//
		$wow->registerExpression(
			$wow->rx("@foreach:(.+)", "i"),
			"<?php foreach($1) { ?>"
		);

		//
		//	@while:$i<10
		//		<li></li>
		//	@end
		//
		$wow->registerExpression(
			$wow->rx("@while:(.+)", "i"),
			"<?php while($1) { ?>"
		);

		//
		//	@css:/css/style.css
		//
		$wow->registerExpression(
			$wow->rx("@css:(.+)", "i"),
			'<link rel="stylesheet" type="text/css" href="$1">'
		);

		//
		//	@css_embed:css.style.css
		//
		$wow->registerExpression(
			$wow->rx("@css_embed:(.+)", "i"),
			'<style type="text/css"><?php echo $app->getFileHandler()->public(\'$1\')->read(); ?></style>'
		);

		//
		//	@css_external:http://....js
		//
		$wow->registerExpression(
			$wow->rx("@css_external:(.+)", "i"),
			'<style type="text/css"><?php echo file_get_contents(\'$1\'); ?></style>'
		);

		//
		//	@js:js/file.js
		//
		$wow->registerExpression(
			$wow->rx("@js:(.+)", "i"),
			'<script type="text/javascript" src="$1"></script>'
		);

		//
		//	@js_embed:js.file.js
		//
		$wow->registerExpression(
			$wow->rx("@js_embed:(.+)", "i"),
			'<script type="text/javascript"><?php echo file_get_contents(\'$1\'); ?></script>'
		);

		//
		//	@js_external:js.file.js
		//
		$wow->registerExpression(
			$wow->rx("@js_external:(.+)", "i"),
			'<script type="text/javascript"><?php echo $app->getFileHandler()->public(\'$1\')->read(); ?></script>'
		);

		//
		//	@url
		//
		$wow->registerExpression(
			$wow->rx("@url", "i"),
			'<?php echo $app->getUrl(); ?>'
		);
		$wow->registerExpression(
			$wow->rx("@iurl", "i"),
			'$app->getUrl()'
		);

		//
		//	@app:getDevelopmentStatus()
		//
		$wow->registerExpression(
			$wow->rx('@app:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
			'<?php echo $app->$1; ?>'
		);
		$wow->registerExpression(
			$wow->rx('@iapp:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
			'$app->$1'
		);

		//
		//	@controller:method()
		//
		$wow->registerExpression(
			$wow->rx('@controller:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
			"<?php echo \$app->getControllerHandler()->getController()->$1; ?>"
		);
		$wow->registerExpression(
			$wow->rx('@icontroller:\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
			"\$app->getControllerHandler()->getController()->$1"
		);

		//
		//	@controller_Pages.HomeController:method()
		//
		$wow->registerExpression(
			$wow->rx('@controller_('.Wow::PHP_NAME_RX.'):\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
			"<?php echo \$app->getControllerHandler()->getController('$1')->$2; ?>"
		);
		$wow->registerExpression(
			$wow->rx('@icontroller_('.Wow::PHP_NAME_RX.'):\$?('.Wow::PHP_NAME_RX.'\(.*?\)|'.Wow::PHP_NAME_RX.')', "i"),
			"\$app->getControllerHandler()->getController('$1')->$2"
		);

		//
		//	@formtoken:name
		//
		$wow->registerExpression(
			$wow->rx('@formtoken:([a-zA-Z0-9\_\-]+)', "i"),
			'<input type="hidden" name="token-$1" value="<?php echo $app->getFormTokenHandler()->generate("$1"); ?>" />'
		);
	}
