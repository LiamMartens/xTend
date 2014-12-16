<?php
	namespace xTend
	{
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
			Wow::RegEx("(@end)","i"),
			"<?php } ?>"
		);
	}
?>