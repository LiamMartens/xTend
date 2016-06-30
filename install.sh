#!/bin/bash
CURRENT_DIR=`pwd`;
INSTALL_DIR=`pwd`;
if [ $# -eq 1 ]; then
    INSTALL_DIR="$INSTALL_DIR/$1";
fi
echo -n "About to install xTend files in $INSTALL_DIR, is that ok? (Y/n): ";
read INPUT;
if [ $INPUT == 'Y' ] || [ $INPUT == 'y' ]; then
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
    if [ $INPUT == 'Y' ] || [ $INPUT == 'y' ]; then
        rm -rf $CURRENT_DIR;
        echo "xTend git directory removed. Dist files have been moved to installation directory.";
    else
        echo "xTend git directory kept. Dist files have been moved to installation directory.";
    fi
    echo -n "Do you want to run project initialization now? (Y/n): ";
    read INPUT;
    if [ $INPUT == 'Y' ] || [ $INPUT == 'y' ]; then
        cd "$1";
        php workbench init;
    fi
else
    echo "Installation cancelled. Nothing happened.";
fi
