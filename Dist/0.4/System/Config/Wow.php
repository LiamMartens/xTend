<?php
	namespace xTend
	{
		Wow::RegisterExpression(
			Wow::RegEx("(@\$)([a-zA-Z0-9\_]+)","i"),
			"<?php echo $$2; ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@if\()(.*)(\))","i"),
			"<?php if($2) { ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@elseif\()(.*)(\))","i"),
			"<?php } elseif($2) { ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@else)","i"),
			"<?php } else { ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@for\()(.*)(;)(.*)(;)(.*)(\))"),
			"for($2$3$4$5$6) {"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@foreach\()(.*)( )(as)( )(.*)(\))"),
			"foreach($2 as $6) {"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@while\()(.*)(\))"),
			"while($2) {"
		);
		Wow::RegisterExpression(
			Wow::RegEx("$(@end)^","i"),
			"<?php } ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@css:)(.*)","i"),
			'<link rel="stylesheet" href="<?php echo \xTend\File::Web("$2"); ?>" type="text/css">'
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@csse:)(.*)","i"),
			'<link rel="stylesheet" href="$2" type="text/css">'
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@script:)(.*)","i"),
			'<script src="<?php echo \xTend\File::Web("$2"); ?>" type="text/javascript"></script>'
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@scripte:)(.*)","i"),
			'<script src="$2" type="text/javascript"></script>'
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@lang)","i"),
			"<?php echo \xTend\Config::Lang; ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@charset)","i"),
			"<?php echo \xTend\Config::Charset; ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@description)","i"),
			"<?php echo \xTend\Config::Description; ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@keywords)","i"),
			"<?php echo \xTend\Config::Keywords; ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@author)","i"),
			"<?php echo \xTend\Config::Author; ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@url)","i"),
			"<?php echo \xTend\Config::Url; ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@title:)(.*)","i"),
			"<title>$2</title>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@favicon:)([a-zA-Z0-9\\\/\-\_]+)(:)([\#0-9]+)","i"),
			'
				<link rel="shortcut icon" type="image/x-icon" href="<?php echo \xTend\File::Web("$2.ico"); ?>">
				<link rel="apple-touch-icon-precomposed" href="<?php echo \xTend\File::Web("$2-152.png"); ?>">
				<meta name="msapplication-TileColor" content="$4">
				<meta name="msapplication-TileImage" content="<?php echo \xTend\File::Web("$2-144.png"); ?>">
				<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo \xTend\File::Web("$2-152.png"); ?>">
				<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo \xTend\File::Web("$2-144.png"); ?>">
				<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo \xTend\File::Web("$2-120.png"); ?>">
				<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo \xTend\File::Web("$2-114.png"); ?>">
				<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo \xTend\File::Web("favicon-72.png"); ?>">
				<link rel="apple-touch-icon-precomposed" href="<?php echo \xTend\File::Web("$2-57.png"); ?>">
				<link rel="icon" href="<?php echo \xTend\File::Web("$2-32.png"); ?>" sizes="32x32">
			'
		);
	}
?>