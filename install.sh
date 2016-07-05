#!/bin/bash
CURRENT_DIR=`pwd`;
INSTALL_DIR=`pwd`;
if [ $# -eq 1 ]; then
    INSTALL_DIR="$INSTALL_DIR/$1";
fi
echo -n "About to install xTend files in $INSTALL_DIR, is that ok? (Y/n): ";
read INPUT;
if [[ $INPUT == 'Y' ]] || [[ $INPUT == 'y' ]]; then
    if [ ! -d "$INSTALL_DIR" ]; then
        mkdir "$INSTALL_DIR";
    fi
    mv "$CURRENT_DIR/dist/Application" "$INSTALL_DIR/Application";
    mv "$CURRENT_DIR/dist/CLI" "$INSTALL_DIR/CLI";
    mv "$CURRENT_DIR/dist/www" "$INSTALL_DIR/www";
    mv "$CURRENT_DIR/dist/.commands" "$INSTALL_DIR/.commands";
    mv "$CURRENT_DIR/dist/.workbench" "$INSTALL_DIR/.workbench";
    mv "$CURRENT_DIR/dist/workbench" "$INSTALL_DIR/workbench";
    echo -n "Want to remove xTend git directory? (Y/n): ";
    read INPUT;
    if [[ $INPUT == 'Y' ]] || [[ $INPUT == 'y' ]]; then
        cd "$1";
        rm -rfR $CURRENT_DIR;
        echo "xTend git directory removed. Dist files have been moved to installation directory.";
    else
        echo "xTend git directory kept. Dist files have been moved to installation directory.";
    fi
    echo -n "Do you want to initialize xTend now? (Y/n): ";
    read INPUT;
    if [[ $INPUT == 'Y' ]] || [[ $INPUT == 'y' ]]; then
        cd "$1";
        php workbench init;
        echo -n "Enter the URL you want to configure for xTend (http://localhost by default, leave empty to keep default setting): ";
        read INPUT;
        if [[ $INPUT != '' ]]; then
            php workbench config:Url $INPUT;
            echo "xTend configured with `php workbench config:Url`";
        fi
        echo -n "Do you want to enable development mode? (This enables the development route with the currently registered status codes) (Y/n): ";
        read INPUT;
        if [[ $INPUT == 'Y' ]] || [[ $INPUT == 'y' ]]; then
            php workbench config:DevelopmentStatus true;
        fi
        echo -n "Do you want to change the backup interval? (1 week by default, false to disable, empty to keep default): ";
        read INPUT;
        if [[ $INPUT != '' ]]; then
            php workbench config:BackupInterval $INPUT;
        fi
        echo -n "Do you want to change the backup limit? (10 by default, empty to keep default): ";
        read INPUT;
        if [[ $INPUT != '' ]]; then
            php workbench config:BackupLimit $INPUT;
        fi
        echo -n "Do you want to change the log limit? (30 by default, empty to keep default): ";
        read INPUT;
        if [[ $INPUT != '' ]]; then
            php workbench config:LogLimit $INPUT;
        fi
        echo -n "Do you want to change the public directory? (www by default, empty to keep default): ";
        read INPUT;
        if [[ $INPUT != '' ]]; then
            php workbench set:public $INPUT;
        fi
        echo "xTend is now ready";
    fi
else
    echo "Installation cancelled. Nothing happened.";
fi
