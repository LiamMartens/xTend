<?php
    namespace Application;
    $app=\xTend\Core\getCurrentApp(__NAMESPACE__);
    $app->getRouter()->restrict($app->getDevelopmentStatus(), function($app) {
        //routes which are only available during development status
        $app->getRouter()->get("codes", function($app) {
            $el = $app->getHTMLHandler()->createElement("table");
            foreach ($app->getErrorCodeHandler()->getErrorCodes() as $code) {
                $row = $el->createElement("tr");
                $row->createElement("td")->addText($code->getHexCode());
                $row->createElement("td")->addText($code->getName());
                $row->createElement("td")->addText($code->getReadableName());
            } $el->write(true);
        });
    });