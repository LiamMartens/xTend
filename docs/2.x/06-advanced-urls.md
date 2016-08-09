# Advanced URL's
In previous sections we went over all types of routes and what routes can do, but so far we've only used simple route handles. In this section we will dive into the more advanced handles.

## Named URL variable
First off you can catch a variable in the URL. The syntax is simple, just put the name of the variable you want between `{}` in whatever section you want to catch the variable.
```
namespace Application;
use Application\Core\Router;
use Application\Core\Request;

Router::get('user/{user}', function() {
    return Request::get('user');
});
```
*Notice the router checks per section, which means it will split the handle in parts on the /*

The above example will print out whatever the user enters in the URL bar after the `user/` path. So when you would browse to `yourdomain.com/user/liam` you will see `liam` appear on the screen.

## Regex
We can also use a regex inside your URL handle to specify what can be entered in the URL bar. The syntax is similar to the Named URL variable (`rx{YOUR REGEX}`).
```
namespace Application;
use Application\Core\Router;

Router::get('user/rx{[0-9]}', 'Only accessible with a number');
```

In this example we would only be able to see the message when we browse to `user/` with any number between 0 and 9 afterwards.

## Named and regexed URL variable
This is a combination of the above and it's syntax is practically identical. Just combine both syntax like this `rx{user}{[0-9]}`.
```
namespace Application;
use Application\Core\Router;
use Application\Core\Request;

Router::get('user/rx{user}{[0-9]})', function() {
    return Request::get('user');
});
```

In this last example we will only get to see the page when we navigate to a `user/` with a number after it and xTend will also make a variable available to us.