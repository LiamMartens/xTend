#Cookies and sessions
xTend has some built in methods to get and set cookies and sessions. These built in functions will encrypt the value of your cookies and sessions as well as hash their name.

###Cookies
Use the `Cookie` class to get, set and remove cookies. All methods in this class are `static`.

####Examples
```
    use \xTend\Core\Cookie as Cookie;

    Cookie::set('name', 'value', time()+3600*24, '/');
    //by default a cookie will be set on domain / for a day if you omit the last 2 parameters

    Cookie::get('name', false, '/');
    //the second parameter is the default value to return when a cookie is not found.
    //The third parameter is optional as it defines the domain where to remove the cookie of the decryption of the value failed

    Cookie::remove('name', '/');
    //The second parameter will yet again default to / if omitted
```

###Sessions
Use the `Session` class to get, set and remove sessions. All the methods are `static` as well.

####Examples
```
    use \xTend\Core\Session as Session;

    Session::set('name', 'value');

    Session::get('name', false);
    //The second parameter is the default value to return when the cookie is not found or fails to be decrypted

    Session::remove('name');
```
