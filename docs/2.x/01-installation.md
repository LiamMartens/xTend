# Installation

## System requirements
* PHP 7 (for the `random_bytes` method)
* PHP zip extension for auto backup functionality
* A webserver with rewriting support to run xTend

## First off
You can either clone or download the [GitHub repo](https://github.com/LiamMartens/xTend). Next you need to set it up using the `install.sh` script or you can do it manually.

## How to install xTend (install script)
Using the `install.sh` script is pretty easy, just run the script in your terminal or command line and pass the relative location where you want to install it to. The script will ask for confirmation and further you into installing xTend with some configuration steps. All this can also be done manually.

## How to install xTend (manually)
If you want to install manually you can move all files in `dist/` to wherever you want xTend to reside. The `www` folder is the public one whereas `Application` and `CLI` are the system folders which should reside in the parent of the public directory. Don't forget to move the other files such as `.workbench`, `.commands` and `workbench`. Lastly, it is important to note that you shouldn't rename the public directory manually if you intend on using the workbench (command line tool). You can change the public directory using the workbench which is explained in the section about configuration.

### Example folder structure
```
/var/www
    Application/
        Blueprints/
        Config/
        Core/
        Libs/
        Objects/
        packagist.json
    CLI/
    www/
    .commands
    .workbench
    workbench
```

*Notice This setup will work but it is advised to follow the configuration steps as well*