#Route options
We used simple text for the route output in section 2 but there are several more
options to choose from.

###Array of options
Besides simple text you can also pass an array of options. Using this array you can choose to load in one or more views, one or more controllers, one or more models. When adding a controller you can also define one or more methods to be executed immediately if necessary. More in depth you can also add a data option to pass data to the controller if there is one defined (otherwise the data is sent to the view) and you can also define which version of the view you want to load (This will become more clear when you read about the views). Following examples will show how to use the array of options.

####Example 1
```
    $app->getRouter()->get('contact', [
        'controller' => 'ContactController',
        'model' => 'EmailModel',
        'view' => 'contact',
        'data' => [
            'name' => 'Liam Martens'
        ]
    ]);
    /*
        in this case the data is sent to the ContactController
        Do keep in mind the controller needs to extend the
        BaseDataExtension class in the Blueprints directory OR you
        can choose to extend the BaseDataController.
    */
```
*Notice More about controllers, models and views in other sections*

####Example 2
```
    $app->getRouter()->get('contact', [
        'controllers' => ['ContactController', 'EmailController'],
        'model' => ['ContactModel', 'UserModel'],
        'views' => ['contact', 'footer'],
    ]);
    //loading multiple views is less useful but it is possible
```

*Notice by default xTend will look inside your application's namespace, but you can also use different namespaces by prefixing model or controller*

####Example 3
```
    $app->getRouter()->get('contact', [
        'controller' => 'ContactController@execute_method',
        'model' => '\OtherNamespace\EmailModel',
        'view' => 'contact',
        'version' => 3
    ]);
    //the version parameter defines what version of the
    //view to load. This will become more clear later on.
```

*Notice xTend automatically injects the request object into the controller method. More about the request object in the extra information*

####Example 4
```
    $app->getRouter()->get('home', [
        'view' => 'home.production',
        'environment' => 'production'
    ]);

    $app->getRouter()->get('home', [
        'view' => 'home.development',
        'environment' => 'development'
    ]);
```

Using this setup xTend will load the view `home/production` if the environment is set to production
and if the environment is set to development xTend will load `home/development`.

*Notice you can also retrieve the environment using the `getEnvironment()` method of the app*

###Function
You choose to execute a function upon route match. When the function is executed the router will also pass the current application as parameter.

####Example

```
    $app->getRouter()->get('contact', function($app, $request) {
        //your code..
    });
```

Passing a function instead of passing an array of options can be useful if you need to execute logic before loading views, models or controllers. It can also be useful if you really want to customize xTend and you want to manually load your models, controllers and views with custom objects and so on.

*Notice More about manually loading views, controllers, models and the request object can be found in the extra information*