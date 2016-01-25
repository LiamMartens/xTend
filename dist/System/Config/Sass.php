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
			$sass_files = $app->getDirectoryHandler()->files("$pubdir/sass");
			$scss = new Compiler();
			$scss->setFormatter("Leafo\\ScssPhp\\Formatter\\Crunched");
			$scss->setImportPaths("$pubdir/sass/");
			foreach($sass_files as $file) {
				if((substr($file, 0, 1)!="_")&&((substr($file, strlen($file) - 5)==".scss") || (substr($file, strlen($file) - 5)==".sass"))) {
					$string_sass=$fh->read("$pubdir/sass/$file");
					$string_css=$scss->compile($string_sass);
					$fh->write("$pubdir/css/$file.css", $string_css);
				}
			}
		}
	}
