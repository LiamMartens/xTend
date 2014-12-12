<?php
	//if statements
	Wow::RegisterExpression(
		"(if\()(.*)(\))",
		"if($2) {"
	);
	Wow::RegisterExpression(
		"(elseif\()(.*)(\))",
		"} elseif($2) {"
	);
	Wow::RegisterExpression(
		"(else)",
		"} else {"
	);
	
	//for statement
	Wow::RegisterExpression(
		"(for\()(.*)(;)(.*)(;)(.*)(\))",
		"for($2$3$4$5$6) {"
	);
	
	//foreach statement
	Wow::RegisterExpression(
		"(foreach\()(.*)( )(as)( )(.*)(\))",
		"foreach($2 as $6) {"
	);
	
	//While statement
	Wow::RegisterExpression(
		"(while\()(.*)(\))",
		"while($2) {"
	);
	
	//End statement
	Wow::RegisterExpression(
		"(end)",
		"}"
	);
?>