# Route options
In the section about registering your first routes we used simple text as route output but there are several options you can use which will be detailed in this part.

## Array of options
Besides simple text you can also pass an array of options. Using this array you can choose to load in one or more views, one or more controllers, one or more models, pass data, define the view version and add an environment restriction. When adding a controller you can also define one or more methods to be executed immediately if necessary. The data you pass will be sent to the controllers if there are any, otherwise it will be sent to the view. (The view version option will become more clear when you read about views and templating). Following examples will show you how to use the array of options.

### Example 1
```
    namespace Application;
    use Application\Core\Router;

    Router::get('contact', [
        'controller' => 'ContactController',
        'model' => 'EmailModel',
        'view' => 'contact',
        'data' => [
            'name' => 'Liam Martens'
        ]
    ]);
    /*
        in this case the data is sent to the ContactController
        Do keep in mind the controller needs to either
        extend the Controller class in the Blueprints directory,
        extend the StaticDataExtension class or you can provide your
        own set and get methods
    */
```
*Notice More about controllers, models and views in other sections*

### Example 2
```
    namespace Application;
    use Application\Core\Router;

    Router::get('contact', [
        'controllers' => ['ContactController', 'EmailController'],
        'model' => ['ContactModel', 'UserModel'],
        'views' => ['contact', 'footer'],
    ]);
    // loading multiple views is less useful but it is possible
```

*Notice by default xTend will look inside your application's namespace, but you can also use different namespaces by using the full class name for the model or the controller*

### Example 3
```
    namespace Application;
    use Application\Core\Router;

    Router::get('contact', [
        'controller' => 'ContactController@execute_method',
        'model' => '\OtherNamespace\EmailModel',
        'view' => 'contact',
        'version' => 3,
        'environment' => 'production'
    ]);
    // the version parameter defines what version of the
    // view to load. This will become more clear later on.
```


### Example 4
```
    namespace Application;
    use Application\Core\Router;

    Router::get('home', [
        'view' => 'home.production',
        'environment' => 'production'
    ]);

    Router::get('home', [
        'view' => 'home.development',
        'environment' => 'development'
    ]);
```

Using this setup xTend will load the view `home/production` if the environment is set to production
and if the environment is set to development xTend will load `home/development`.

*Notice you can retrieve the environment using the `environment()` method of your application*

###Function
You can also choose to execute a function upon route match. 
####Example

```
    namespace Application;
    use Application\Core\Router;

    Router::get('contact', function() {
        //your code..
    });
```

Passing a function instead of passing an array of options can be useful if you need to execute logic before loading views, models or controllers. It can also be useful if you really want to customize xTend and you want to manually load your models, controllers and views with custom objects and so on.

*Notice More about manually loading views, controllers, models and the request object can be found in the extra information*

