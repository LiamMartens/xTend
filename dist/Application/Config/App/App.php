<?php
    namespace Application;
    use Application\Core\App;
    use Application\Core\FileHandler;
    App::configuration(json_decode(FileHandler::system('Config.App.configuration.json')->read(), true));
    App::configuration(json_decode(FileHandler::system('Config.App.directories.json')->read(), true));