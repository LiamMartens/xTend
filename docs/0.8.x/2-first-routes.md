#Simple routes with simple text
In this section we will go over creating all the different types of routes but we will solely output some simple text to keep it understandable and easy to learn.

###Where are the routes by default?
By default the routes will be stored in the `Application/Config/Routes/Routes.php` file. However you can create routes in any file as long as you have access to the current `$app` and you have to do this before the `Router` executes. You can find out more about accessing the current `$app` in the extra information section, but you can also find out about this in the configuration files as they use the same code. (just look at the top of each configuration file).

###Home route
You can set the home (or empty url) route by using the `home()` function from the router. By default there is one in place in the default routes file.

*Notice: You can also set a GET route with an empty Url*

###GET route
To set a route for HTTP GET requests you have to use the `get()` function. This method accepts 2 or 3 parameters, being the url, the route to execute and optionally and alias.

####Example
```
    $app->getRouter()->get('contact', 'This is a contact page', 'contact-page');
```

In this example we set a GET route for the `/contact` url. When we browse to our url we will see `This is a contact page` in the browser.

The last parameter, being the alias, can be used to easily retrieve routes. The router has some built in functions to retrieve routes by their URL handle (`contact` in this case), but we can also use the `getRouteByAlias()` method to retrieve a route using an alias. The other functions are `getPostRoute()`, `getGetRoute()`, `getPutRoute()`, `getDeleteRoute()`, `getAnyRoute()` and `getErrorRoute()`

####Example
```
    $app->getRouter()->getRouteByAlias('contact-page');
```

*Notice: you can also store the router in it's separate variable if you don't like continuously calling `getRouter()`*

###POST, PUT, DELETE and ANY routes
Setting `POST`, `PUT`, `DELETE` and `ANY` routes, `any` will catch any request, is analog to the setting a `GET` route. Just use `post()`, `put()`, `delete()` or `any()` instead.

*Notice: aside from simple url's such as `/contact` or `/services/webdesign` we can also set some pretty complicated url's but more on that in the a following chapter*

####Examples

```

    $router = $app->getRouter();

    $router->post('post-page', 'This page only allows POST');

    $router->put('put-page', 'This page only accepts PUT');

    $router->delete('delete-page', 'This page only accepts DELETE');

    $router->any('any-page', 'This page accepts any HTTP verb');

```

###Error route
Besides from the regular HTTP routes you also define routes to be executed when the application throws a user-defined error. For example, when no routes are matched the router will automatically call the `throwError()` function of the curent application to throw a `404` error. This will set the HTTP status to 404 and the app will also call the router again to throw an error page if there is an error route defined.

###Example

```
    $app->getRouter()->error(404, 'Page not found');
```

*Notice: more on the xTend's error handler in a further section*

###Default route
When catching 404's you can choose to use an error route, but you can also opt to use a default route. This will not throw a 404 response status.

####Example

```
    $app->getRouter()->def('This is the default route');
```

###Restrict
Let's say you want to make a certain region only available to users who are logged in?
You can do that with the `restrict()` method. The function accepts 2 parameters, both
methods themselves. The first one needs to return `true` or `false` and will determine
whether the routes in the second function are executed.

####Example

```
    $app->getRouter()->restrict(function($app) {
        return true;
    }, function($app) {
        //you can have as many routes in here as you want
        $app->getRouter()->get('user', 'User page');
    });
```

*Notice since routes are executed from top to bottom you can overwrite existing routes in a restrict which is further down.*
