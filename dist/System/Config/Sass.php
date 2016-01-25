<?php
	namespace xTend\Application {
		use Leafo\ScssPhp\Compiler;
		$app=\xTend\getCurrentApp(__NAMESPACE__);
		//get public dir
		$pubdir=$app->getPublicDirectory();
		//get file handler
		$fh=$app->getFileHandler();
		//check app development status
		if($app->getDevelopmentStatus()==true) {
			$sass_files = $app->getDirectoryHandler()->files("$pubdir/css");
			$scss = new Compiler();
			$scss->setFormatter("Leafo\\ScssPhp\\Formatter\\Crunched");
			$scss->setImportPaths("$pubdir/sass/");
			foreach($sass_files as $file) {
				if(substr($file, 0, 1)!="_") {
					$string_sass=$fh->read("$pubdir/sass/$file.scss");
					$string_css=$scss->compile($string_sass);
					$fh->write("$pubdir/css/$file.css", $string_css);
				}
			}
		}
	}
