# Request object
Every time xTend receives an HTTP request there will be a static `Request` object available which contains a lot of information about the request.

## get
Use the `get()` method to retrieve a URL variable (As seen before, URL variables are stored in the `Request` object).
```
namespace Application;
use Application\Core\Request;

$var = Request::get('key');
$var = Request::get('key', 'default value');
```
*Notice the second paramater is `false` by default*

## in
Use the `in()` method to check whether a URL variable exists.
```
namespace Application;
use Application\Core\Request;

$exists = Request::in('key');
```

## all
Use the `all()` method to return all URL variables as an array.

## $post
`$post` is a member of the request object which contains your `POST` data. You could also just use `$_POST` but in some cases your webserver will not parse the post data correctly but xTend will.

## $get
The `$get` member contains your get data. If for any reason your webserver didn't parse the query correctly, xTend will parse it for you.

## $data
The `$data` member will contain your navigation data which was passed when navigating using your `App`. More on navigating using your app in another chapter.

## method
The `method()` function returns the HTTP verb that was requested.

## path
The `path()` method returns the path of the request. This does not include the domain name.

## scheme
The `scheme()` function returns either `http` or `https`, depending on which one is used in the request.

## host
Use the `host()` method to retrieve the domain name.

## url
The `url()` method will concatenate the scheme and the host to form the url.

## port
The `port()` method will return the port number (usually 80 or 443).

## query
The `query()` function will return the query string.

## contentType
You can use the `contentType()` method to set the current content type. Pass the content type as paramater and you're all set.
```
namespace Application;
use Application\Core\Request;

Request::contentType('application/json');
```