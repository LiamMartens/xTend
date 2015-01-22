#Installation
The installation is divided in several steps

[STEP 1: Uploading the files](#step-1)  
[STEP 2: Clean URL's](#step-2)  
[STEP 3: Logs](#step-3)  
[STEP 4: Backups](#step-4)  
[STEP 5: Wow templating engine](#step-5)  
[STEP 6: SASS/SCSS compiler](#step-6)  

###STEP 1:
After you have downloaded the files you have to put them on your hosting. Keep in mind the `/www` directory will be publicly accessible (here you will store css, javascript and images) while the `/System` directory should not be publicly visible. Another sidenote, you can rename the `/www` directory to any other name you like.
**Can I use this on a normal hosting?**
Yes you can. You just have to put the `/System` directory in the parent directory of your default public directory and copy the files from `/www` to your public directory. (could already be `/www` but can also be `/public_html` or...)

###STEP 2:
To get everything working correctly you will need to edit your Apache configuration or you can use a `.htaccess` file.  
Either way, you will need following piece of code:
```
RewriteEngine On
RewriteRule ^(.*)/$ /$1 [L,R=301]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```
**I don't know this, what does it do?**
The above piece of code makes sure any URL request, excluding directories and files, will point to the `index.php` file inside your public directory.

###STEP 3:
xTend logs PHP errors, PHP exceptions and also it's own errors. To make this work you will have to allow writing on the `/System/Logs` directory.

###STEP 4:
There is an auto-backup feature available which requires the `/System/Backups` directory to be writable. If you don't want this feature you can disable it by editing the `/System/Core/Config.php` file and setting the `Backup` setting to false instead of a time interval.

###STEP 5:
xTend uses it's own templating engine called Wow. It compiles wow enabled Views and Layouts (upon which Views can extend, more about that in the documentation) and saves them into the `/System/ViewOutput` directory. This requires the folder to be writable. You can easily remove the engine by removing the `Wow.php` file from the `/System/Libs` and the `/System/Config.php` folders.

###STEP 6:
A SASS/SCSS compiler is included with version 0.4 ([Third-Party SASS compiler](http://leafo.net/scssphp/)) and to make this work you should allow writing on the `/www/css` directory. You can remove this library the same way as the Wow templating engine by removing the related files from the `/System/Libs` and the `/System/Config` folders.

##All set up and ready creating a great website!