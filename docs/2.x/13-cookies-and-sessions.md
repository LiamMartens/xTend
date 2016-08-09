# Cookies and sessions
If you want your cookie and session values encrypted, you can use xTend's built in helper classes.

## Cookies
Use the `Cookie` class to get, set and remove cookies.
```
namespace Application;
use Application\Core\Cookie;

Cookie::set('name', 'value', time()+3600*24, '/');
//by default a cookie will be set on domain / for a day if you omit the last 2 parameters

Cookie::get('name', false, '/');
//the second parameter is the default value to return when a cookie is not found.
//The third parameter is optional as it defines the domain where to remove the cookie of the decryption of the value failed

Cookie::remove('name', '/');
//The second parameter will yet again default to / if omitted
```

## Sessions
Use the `Session` class to get, set and remove sessions.
```
use \xTend\Core\Session as Session;

Session::set('name', 'value');

Session::get('name', false);
//The second parameter is the default value to return when the cookie is not found or fails to be decrypted

Session::remove('name');
```
