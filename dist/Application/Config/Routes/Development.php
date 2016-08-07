<?php
    /**
    * Register development routes which are available when
    * the development status is on
    */
    namespace Application;
    use Application\Core\App;
    use Application\Core\Router;
    use Application\Core\HTMLHandler;
    use Application\Core\StatusCodeHandler;

    Router::restrict(function() {
        return ((App::environment()=='dev')||(App::environment()=='development'));
    }, function() {
        Router::get('xtend/codes', function() {
            $table = HTMLHandler::createDocument()->createElement('table');
            $codes=StatusCodeHandler::all();
            foreach($codes as $code) {
                $row=$table->createElement('tr');
                $row->createElement('td')->addText($code->hex());
                $row->createElement('td')->addText($code->name());
                $row->createElement('td')->addText($code->readable());
            }
            $table->write(true);
        });
    });
