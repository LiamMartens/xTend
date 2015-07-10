<?php
	use Leafo\ScssPhp\Compiler;
	if(xTend\Config::Development==true) {
		//all scss file names you want to compile
		$sass_files = array("main");
		$scss = new Compiler();
		$scss->setImportPaths("sass/");
		foreach($sass_files as $file) {
			$string_sass = file_get_contents("sass/$file.scss");
			$string_css = $scss->compile($string_sass);
			file_put_contents("css/$file.css", $string_css);
		}
	}