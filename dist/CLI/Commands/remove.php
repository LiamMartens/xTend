<?php
    namespace Application;
    use xTend\Workbench\Workbench;

    Workbench::register('^remove ([a-zA-Z0-9\.\_]+)$', function($argv) {
        // do checks
        $name=$argv[1]; if(!is_dir(Workbench::$directory.'/'.$name)||!isset(Workbench::get('applications')[$name])) { die('The application does not exist'); }
        if($name==Workbench::get('application')) { die('You can\'t remove the currently selected application'); }
        // remove app dir
        (new Objects\DirectoryHandler\Directory(Workbench::$directory.'/'.$name))->remove();
        // remove from configuration
        Workbench::remove($name);
        //remove from index.php
        $contents=file_get_contents(Workbench::$directory.'/'.Workbench::get('public').'/index.php');
        $contents=preg_replace('/namespace '.Workbench::namespace($name).' \{.+?\}.+?\}/s',
                                '', $contents);
        file_put_contents(Workbench::$directory.'/'.Workbench::get('public').'/index.php', trim($contents));
    }, 'remove');