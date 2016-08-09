# First routes
In this section we will go over creating all the different types of routes but we will solely output some simple text to keep it understandable and easy to learn.

## Default route location
By default routes will be stored in your application's `Config/Routes/Routes.php` but you can create routes in any config file (even anywhere else before the router executes altough not advisable just for structure).

## Home route
You can assign a home route, which is essentially a route with an empty a handle, by using the `home()` function. Important to note is that the router will take the `location` configuration variable into account.
```
namespace Application;
use Application\Core\Router;

Router::home('My homepage');
```

## GET route
You can assign a route to the HTTP GET verb by using the `get()` method. The function accepts 4 parameters at most being the url handle, the route option(s), the route alias and lastly the override parameter.

*The override parameter is set to false by default, but if you set it to true it will override the existing route if there is already one registered. This is because the method also serves as a getter for routes if you enter just an alias*

```
namespace Application;
use Application\Core\Router;

Router::get('contact', 'My contact page', 'pages.contact');
```

## POST, PUT, DELETE, PATCH, OPTIONS routes
All these HTTP verbs are supported as well and their methods are identical to the `get()` function. (with a different name that is)
```
namespace Application;
use Application\Core\Router;

Router::post('post', 'My post page', 'pages.post');
Router::put('put', 'My put page', 'pages.put');
Router::delete('delete', 'My delete page', 'pages.delete');
Router::patch('patch', 'My patch page', 'pages.patch');
Router::options('options', 'My options page', 'pages.options');
```

## Any route
Use the `any()` method to create a route which responds to all HTTP verbs (even those not listed here). The method is identical.
```
namespace Application;
use Application\Core\Router;

Router::any('any', 'I accept any HTTP verb', 'pages.any');
```

## Assigning multiple verbs
You can use the `match` method to assign to multiple verbs instead of all verbs. Just pass an array of the verbs you want to assign as first parameter. The other parameters are identical to the other methods.
```
namespace Application;
use Application\Core\Router;

Router::match([ 'POST', 'GET', 'put' ], 'My multi route', 'pages.multi');
```

## Error route
Whenever the application throws an error, this doesn't mean an exception it means an application status code was thrown (more on status codes in a different section), the router will be called upon to look for a route assigned to said status code. By default there is status code `0x0194` or `404` in decimal. You can register and throw your own status codes but if you want the router to show a specific page when this happens you have to register a route for the error or status by using the `error()` method. 
```
namespace Application;
use Application\Core\Router;

Router::error(0x0194, 'Page Not Found', 'errors.404');
```

## Default route
If you want to assign a default route, this will not throw a 404 when executed, you can use the `def()` method. Usage is identical to assigning a home route.
```
namespace Application;
use Application\Core\Router;

Router::def('My default route');
```

## Restricting your routes
You can restrict your routes under certain conditions by using the `restrict()` method. This function accepts 2 parameters, the first one being the condition, or function which returns `true` or `false` and the second one is a function where you can register the routes. The second function will obviously only be executed when the condition is `true`.
```
namespace Application;
use Application\Core\Router;

Router::restrict(function() {
    return true;
}, function() {
    Router::get('get', 'My restricted route');
});
```