#Introducing xTend

###*What is xTend?*
xTend is a simple and easy to use yet powerful and very extendable PHP MVC framework

###*Why should I use it?*
I can only give you a couple of reasons but be free to see for yourself
* Clean and powerful URL's
* Easy to understand structure (Model-View-Controller)
* Easy to set up
* Easy to configure
* Easy to extend

##*Installation*

####Step 1:
After you have downloaded the files you will have to put them on your webspace. Keep in mind the `/www` directory will be publicly accessible (here you will store your css, javascript, images) while the `/System` directory should not be publicly visilbe.

######How to direct to the `/www` directory by default?
If you are running an Apache server you need to edit your configuration, here are some links which might help you setting it up. Keep in mind not every set up is the same so you might need to Google some more.
* [How do I change the root directory of an Apache server?](http://stackoverflow.com/questions/5891802/how-do-i-change-the-root-directory-of-an-apache-server)
* [How to set up Apache virtual hosts on Ubunut 14.04 LTS?](https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts)

######I only have a normal webhosting and thereby can not change the Apache configuration
No problem, just put the `/System` directory above your default web directory for example:
Your web directory is located at `/domains/example.com/public_html` then in effect the `public_html` directory will be your `www` directory. This means your `/System` directory will be located at `/domains/example.com/System`. You don't have to change anything in order for this to work.

####Step 2:
To get the nice url's working you have 2 options:  
1. Add an `.htaccess` file to your web root directory (`/www`)  
2. Edit your apache configuration  
Either way you will need to add this piece of code:
```
RewriteEngine On
RewriteRule ^(.*)/$ /$1 [L,R=301]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

####Step 3 (LOGS):
To make the Logging work you need to allow writing on the `/System/Logs` directory.

####Step 4 (AUTO-BACKUP):
To make the backup function work you will need to allow writing on the `/System/Backups` directory. You can disable backups by editing the `/System/Core/Config.php` file and setting `Backup` to `false`.
```
Backup = false;
```

####Step 5 (WOW COMPILER):
To make the Wow Compiler work you need to allow writing on the `/System/ViewOutput` and the `/System/Meta` directory.
If you do not intend to use this feature then you can just remove the directory or leave it unwritable. You can also remove the Wow library altogether by removing the `Wow.php` from the `/System/Libs` directory, but don't forget to remove the `Wow.php` configuration file from the `/System/Config` directory as well.

####Step 6 (SASS COMPILER):
To make the sass compiler work ([Third-Party SASS compiler](http://leafo.net/scssphp/)) you also need to allow writing on the `/www/css` directory. Again if you don't need a SASS compiler you can remove the `scss.php` file from the `/System/Libs` directory, but don't forget to remove the `sass.php` file from the `/System/Config` directory as well.

####All set up!
