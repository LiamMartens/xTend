#The Config directory
By default all files in your `Config` directory are included, but there is support for file modifiers just like in your `Libs` directory. Important to note is that the `Libs` are loaded before the `Config`.

###Extra config info
xTend has 2 other configuration methods up it's sleeve.

###PreConfiguration
In xTend you can add a pre configuration method to your application. Obviously this needs to be done before your configuration loads in. You can do this by calling your application's `addPreconfigurationMethod()` method and pass your function as parameter. xTend will automatically execute all pre configuration methods right before loading in the config files.

###PostConfiguration
Just like pre configuration methods, there are post configuration ones as well. These are executed right after the configuration has been loaded and you can add a post configuration method using the `addPostConfigurationMethod()` function.
