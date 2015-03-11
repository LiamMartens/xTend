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
			"<?php for($2$3$4$5$6) { ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@foreach\()(.*)( )(as)( )(.*)(\))"),
			"<?php foreach($2 as $6) { ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@while\()(.*)(\))"),
			"<?php while($2) { ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@end)","i"),
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
			Wow::RegEx("(@controller:)(.*)","i"),
			"<?php echo \xTend\App::Controller()->$2(); ?>"
		);
		Wow::RegisterExpression(
			Wow::RegEx("(@module\()([a-zA-Z0-9\.]+)(\))","i"),
			"<?php xTend\Modules::Insert(\"$2\"); ?>"
		);
	}
?>