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
After you have downloaded the files you will have to put them on your webspace. Keep in mind the `/www` directory will be publicly accesible (here you will store your css, javascript, images) while the `/System` directory should not be publicly visilbe.

######How to direct to the `/www` directory by default?
If you are running an Apache server you need to edit your configuration, here are some links which might help you setting it up. Keep in mind not every set up is the same so you might need to Google some more.
* [How do I change the root directory of an Apache server?](http://stackoverflow.com/questions/5891802/how-do-i-change-the-root-directory-of-an-apache-server)
* [How to set up Apache virtual hosts on Ubunut 14.04 LTS?](https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts)

######I only have a normal webhosting and thereby can not change the Apache configuration
No problem, just put the `/System` directory above your default web directory for example:
Your web directory is located at `/domains/example.com/public_html` then in effect hte `public_html` directory will be your `www` directory. This means your `/System` directory will be located at `/domains/example.com/System`

####Step 2:
To get the nice url's working you have 2 options:
1. Add a `.htaccess` file to your web root directory (`/www`)
2. Edit your apache configuration
Either way you will need to add this piece of code:
```
RewriteEngine On
RewriteRule ^(.*)/$ /$1 [L,R=301]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

####Step 3:
To make the Wow View Compiler work you need to allow writing on the `/System/ViewOutput` directory.
If you do not intend to use this feature then you can just remove the directory or leave it unwritable.

####All set up!

##Your first routes
To define your Routes you can use the Routes file located at `/System/Config/Routes.php` but you can also do this in a different file inside `/System/Config`.

####Route options
You can pass several types of route options.
1. Just a string
  * For example `Router::Home("This is my homepage")`. Now when going to the homepage you will see `This is my homepage`
2. An array of options
  * The array can contain at most 4 keys with their respective values:
    * Model : The Model to load up (without .php extension)
    * Controller : The Controller to load up (without .php extension)
    * View : The View to load up (without .php or .wow.php extension)
    * Data :  Any data as array to pass to the Controller or the View when there is no controller present
3. A function

####URL
examples:
* URL `hello` will match `http://domain.com/hello`
* URL `hello/person` will match `http://domain.com/hello/person`
* URL `hello/{name}` will match `http://domain.com/hello/anything` and there will be a variable called name available which will contain the respective value
* URL `hello/person/*` will match `http://domain.com/hello/person/anything`, but in this case there won't be any variable available to return the 3rd url path variable

####Home
First you will probably want to create a route for Home and this is very easy. Just define following route `Router::Home($RouteOptions)`

####Any
This will match any request (POST or GET). The Any routes will be checked first so these are prioritized.
e.g. `Router::Any($URL, $RouteOptions)`

####Get
This will match only `GET` requests
e.g. `Router::Get($URL, $RouteOptions)`

####Post
This will match only `POST` requests
e.g. `Router::Post($URL, $RouteOptions)`
