#Updater
More information on the Update function of xTend

##Checking for an update
To check for an update just call `Updater::Check();` and it will update your xTend version to the newest one available.
!! DO NOTE CURL NEEDS TO BE ENABLED AND INSTALLED
!! DO NOTE THE SYSTEM DIRECTORY HAS TO WRITABLE (777)
!! REMEMBER TO CHANGE THE SYSTEM DIRECTORY'S PERMISSIONS BACK TO NON-WRITABLE (755 or 644)

##Config
The config file is located at `/System/Config/Updater.php`, 
however, you can name this file whatever you like.

##ExcludeFiles
Using the `Updater::ExcludeFiles` function you can set which files not to change when updating xTend to a new version. This can only be set once, so when you try to set it again, it will override the previous array of excluded files. An example:
```
xTend\Updater::ExcludeFiles(array(
    "Config/Routes.php",
    "Config/Updater.php"
));
```
This will make sure those 2 configuration files are not changed when updating. Keep in mind, excluded files are relative to the `/System` directory since only the `/System` directory will be updated. (We will not fiddle with your public files)

##ExcludeDirectories
The `Updater::ExcludeDirectories` function works just like the `Updater::ExcludeFiles` one with the only difference being you can define excluded directories instead of files.