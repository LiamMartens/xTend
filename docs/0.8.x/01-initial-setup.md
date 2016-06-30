#Initial setup
###What you need before you begin

* PHP 7
* PHP Zip Extension
* A webserver to run the PHP framework

###Step 1: Download or clone xTend
You can either download a zip file on [GitHub](https://github.com/LiamMartens/xTend) or you can clone the repository  

```
    git clone https://github.com/LiamMartens/xTend.git
```

####Notice
Steps 2, 3 and 4 are the configuration and installation steps. You can also use the `install.sh` file to do all the configuration and installation at once. Just run the script and pass the location where you want to install xTend as argument (if no argument is passed it will be installed in the current directory. Don't worry the script will ask for a confirmation).

###Step 2: Move the files
All the files you need are located in the `xTend/dist` directory. The `www` directory will be your public directory whereas `Application` and `CLI` will be located in the parent of your public directory. Also make sure to copy the `.commands` and `.workbench` files.

*Notice: You can rename the public directory later but not just yet*

####Example folder structure
```
    /var/www/domain.com
        Application/
            Blueprints/
            Config/
            Core/
            Objects/
        CLI/
        .commands
        .workbench
        bench
```

###Step 3 Initialize and configure xTend
####Keys
To initialize xTend you have to navigate to the xTend directory inside your command line or terminal and run the init command of the xTend workbench.

The `init` command will initialize the `Application/Config/Sessions/Sessions.json` file with random values. These values are used to secure your session, session variables and cookies. (if you use xTend's `Session` and `Cookie` functions).

If you don't have access to a command line you can also manually set these values.

####Example
```
	liam@server:~$ cd /var/www/domain.com
	liam@server:/var/www/domain.com$ php workbench init
```

####Application configuration
Secondly, it is advised to check some of the Application configuration. Following values can be set:  

* Url
* Development mode
* Backup interval
* Backup limit
* Log limit

You can set or get these values using the workbench as follows
```
	liam@server:/var/www/domain.com$ php workbench config:Url
	http://localhost
	liam@server:/var/www/domain.com$ php workbench config:Url http://domain.com
	liam@server:/var/www/domain.com$ php workbench config:Url
	http://domain.com
```

In this case we set the `Url` configuration variable, but setting or getting the `DevelopmentStatus`, `BackupInterval`, `BackupLimit` and `LogLimit` is identical.

If you again don't have access to a command line or terminal you can set these values manually in the `Application/Config/App/Configuration.json` file.

### Step 4 Change the public directory
You can also use the command line workbench to change the public directory. Just use the `set:public` command. You can also rename the directory yourself but it is not advised to do this when using the workbench.

####Example
```
	liam@server:/var/www/domain.com$ php workbench set:public public_html
```

###Step 5 Check your server configuration
To make sure xTend works correctly you will have to enable url rewriting. You can find some example server configuration on [GitHub](https://github.com/LiamMartens/xTend) in the `conf` directory. Here you will find configurations for Apache, Lighttpd and Hiawathan. (the `.htaccess` file for Apache sits in your public directory by default as well). If you use a different webserver such as Nginx or Caddy you'll just have to make sure to rewrite ALL urls to `index.php`.

### Step 6 Check it out
xTend is now ready to use and you can browse to your web url. You should see the text `My homepage` show up if everything is working correctly. If nothing shows up make sure to check the steps again and make sure to turn error reporting on for PHP.
