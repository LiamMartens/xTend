# Packagist lite
xTend has simple dependency free support for packagist packages. You can use it to install and update your PHP packages.

## Installing packages
To install packages you can use the workbench's `packagist:install` command. If you run it without arguments it will install or update all packages in your application's `packagist.json` file. You can also pass the vendor and package as parameter which will install the package and add it to your `packagist.json` file. Lastly you can also specify the version yourself by adding another argument.
```
php workbench packagist:install
php workbench packagist:install vendor/package
php workbench packagist:install vendor/package ^1.0.0
```
*Notice there is support for packagist's version conditions such as ^ and ~*

## Removing packages
To remove a package you can call `packagist:remove` with the vendor and package as argument.
```
php workbench packagist:remove vendor/package
```

## Removing a package dependencies
You can automatically remove package dependencies by running the `packagist:autoremove` command. The command accepts the same parameters as the `install` command.
```
php workbench packagist:autoremove vendor/package
php workbench packagist:autoremove vendor/package ^1.0.0
```
