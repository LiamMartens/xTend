#Modules
##Creating a module
Creating a module is as easy as putting a php file inside the `System/Modules` directory. You can choose to just use a plain `.php` file or a `.wow.php` file which will be compiled by the WOW compiler on insertion.

##Inserting a module
You can insert a module by calling `xTend\Modules::Insert({name})`. You can put the module in subdirectories if you like and you don't have to worry about passing an extension. You can also insert one using a Wow statement if you are using the compiler by adding `@module({name})`. Example for a module located at `System/Modules/Calendars/Gregorian.wow.php` : `xTend\Modules::Insert("Calendars.Gregorian")`