<?php
	if(xTend\Config::Development==true) {
		$sass_files = array("main");
		// scssc will be loaded automatically via Composer
		$scss_compiler = new scssc();
		// set the path where your _mixins are
		$scss_compiler->setImportPaths("sass/");
		// set css formatting (normal, nested or minimized), @see http://leafo.net/scssphp/docs/#output_formatting
		//$scss_compiler->setFormatter($format_style);
		foreach($sass_files as $file) {
			$string_sass = file_get_contents("sass/$file.scss");
			// compile this SASS code to CSS
			$string_css = $scss_compiler->compile($string_sass);
			// write CSS into file with the same filename, but .css extension
			file_put_contents("css/$file.css", $string_css);
		}
	}