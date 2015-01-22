#Structure
The folder structure explained

###Public directory (`/www`)
Not much to say about this directory, it will simply house your public files such as css, javascript, images and sass if you use the sass compiler.

###xTend directory (`/System`)
* `/Backups` : When automatic backups is enabled your backups will show up here as .zip files. The filename of each backup will end with the date and time of creation in following format `YEAR_MONTH_DAY_HOUR_MINUTE_SECOND`.  
**What files are being backed up?**  
Everything really, both the `/System` and the `/www` directory and all their content
* `/Blueprints` : If you have abstract classes for objects you can put them in here. Of course you don't **have** to follow these *rules*, it's just to keep it all organized.
* `/Config`  : If you have configuration files which you can to load, you can put them in here. Again you don't **have** to.
* `/Controllers` : The controllers of your view will should be stored here. It's filename ought to be the same as the name of the class which it houses. For example my controller is called `LoginController` then the file should be called `LoginController.php`. (if you are not familiar with Model-View-Controller principle you should look it up and learn more about it)
* `/Core` : Here you'll find the core files of the framework. These are all necessary for everything to work correctly. You can also find one configuration file in here which houses constant variables for the system to use. These can and should be changed.
* `/Dynamic` : If you don't want to load all libraries at once because they are not always needed, you can put those in the dynamic directory. The same naming principle as the controllers folder applies to the dynamic folder. For example if you'd call `$a = new Person();` and `Person` is not an existing class it will look for a `Person.php` file inside the dynamic directory, keep that in mind.
* `/Layouts` : If you use the Wow Templating engine, it will can use layout files to extend views with. These are stored here.
* `/Libs` : If you want to exclude extra libraries you can store them in here. Included libraries here are the Wow Engine and the SASS compiler.
* `/Logs` : Log files will be saved in this directory. These include PHP errors, PHP exceptions and errors thrown by the framework.
* `/Meta` : this directory is used to save external meta data for files.
* `/Models` : Models will be saved here. (if you are not familiar with Model-View-Controller principle you should look it up and learn more about it)
* `/Objects` : Custom objects can be saved here. Though not a necessity.
* `/ViewOutput` : If you use the Wow Engine this directory is very important since it houses your compiled views. You can remove it's contents to force everything to re-compile. If you don't use the Wow templating engine you can remove it.
* `/Views` : Your views, either Wow or plain PHP, should be saved here.