<?php
    namespace xTend\Application
    {
        $app=\xTend\getCurrentApp(__NAMESPACE__);
        if($app->getDevelopmentStatus()==true) {
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
        }
    }