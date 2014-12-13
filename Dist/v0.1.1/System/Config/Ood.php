<?php
	Ood::AddConnection("Default", array(
		"Host" => "localhost",
		"Engine" => "mysql",
		"Database" => "posts",
		"Username" => "root",
		"Password" => "",
		"Charset" => "utf8",
		"Collation" => "utf8_unicode_ci"
	));
	$a=Ood::Connect("Default");
	$a->Table("Posts")->Select("*")->Where("Id","=","1")->Execute();
	$a->Content = "dqzdzz";
	echo json_encode($a->GetQuery()->Data);
?>