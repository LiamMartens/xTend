<?php
    namespace Application;
    use \RecursiveIteratorIterator;
    use \RecursiveDirectoryIterator;
    use xTend\Workbench\Workbench;

    Workbench::register('^new ([a-zA-Z0-9\.\_]+) (.+?) (.+?)$', function($argv) {
        // get application name
        $name=$argv[1];
        // get domain and path restrictions
        $domain=($argv[2]==='any') ? '*' : $argv[2];
        $path=($argv[3]==='any') ? '*' : $argv[3];
        // check for existance
        if(is_dir($name)||isset(Workbench::get('applications')[$name])) { die('This application already exists'); }
        // get xtend latest release
        $latest_release=file_get_contents('https://xtend.liammartens.com/releases/latest.release');
        // get zip file
        $zip=file_get_contents('https://xtend.liammartens.com/releases/'.$latest_release.'.zip');
        file_put_contents(Workbench::$directory.'/xtend.zip', $zip);
        // extract zip
        $zip=new \ZipArchive; $zip->open(Workbench::$directory.'/xtend.zip');
        $zip->extractTo(Workbench::$directory);
        // move application folder
        rename(Workbench::$directory.'/xTend-'.$latest_release.'/dist/Application', Workbench::$directory.'/'.$name);
        // remove directory
        (new Objects\DirectoryHandler\Directory(Workbench::$directory.'/xTend-'.$latest_release))->remove();
        // remove zip file and directory
        unlink(Workbench::$directory.'/xtend.zip');
        // add application to Workbench configuration
        Workbench::new($name, $domain, $path);
        // get namespace from name
        $namespace=Workbench::namespace($name);
        // add to index.php
        file_put_contents(Workbench::$directory.'/'.Workbench::get('public').'/index.php', '
    namespace '.$namespace.' {
        global $matched_application;
        if(__NAMESPACE__==$matched_application) {
            Core\App::start(__DIR__);
            Core\FileHandler::system(\'Config.App.App.php\')->include();
            Core\App::run();
        }
    }', FILE_APPEND);
        // replace old namespaces (default Application)
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($name)) as $file) {
            // skip if not file / if not PHP file
            if((!is_file($file))||(substr($file, strrpos($file, '.'))!=='.php')) { continue; };
            Workbench::filespace($file, 'Application', $namespace);
        }
    }, 'new');