#Extra information

###Accessing the App
You will see this piece of code pop up a lot throughout the files xTend.

```
    namespace Application;
    $app = \xTend\Core\getCurrentApp(__NAMESPACE__);
```

In essence this is pretty simple to understand, as long as
you get PHP's namespacing.

The `namespace` statement defines what application we are  in (you can create multiple applications using the `workbench`). The name of the directory determines the namespace of your application which is why your default application's namespace is `Application`. Your running applications are stored by their namespace which is why you can call the `getCurrentApp()` method with the current namespace as parameter to return the requested app. If you want you can also hardcode the namespace as a string in the `getCurrentApp` method if you want to stay out of the `Application` namespace.

###Creating a new app
You can use the `workbench` to create a new application under a new namespace. Just use the `new` command as follows
```
    liam@server:/var/www/domain.com$ php workbench new Application.Blog:blog.domain.com:*
```

In this case the command will create a new application in a directory called `Application.Blog`. The application will receive the namespace `Application\Blog` and will be launched when the user browses to `blog.domain.com` (the second parameter). You can also choose to enter a `*` to not specify the domain name. The third parameter is a path to match. In this case we match all (`*`) because we want the application to match all URL's prefixed with the `blog.domain.com` domain but since we can also choose to run several applications on 1 domain, we could use the path to specify the application.

*Notice should only be used when creating complex systems and they aren't very useful when you are using xTend to run a small website. Also don't forget to switch to your new application using the workbench's `set:application` command and then run the `init` command to initialize the app*

###Manually loading a Model
To manually load a model you have to access the application's model handler (call `getModelHandler()` method to retrieve it). Then you can use the `loadModel()` function to load a model.  

The `loadModel()` method accepts 3 parameters at most being, the name of the model, the namespace (the application's namespace by default) and a boolean called `createInstance`.  

Keep in mind the name of the model includes the directory as well. For example, when you load the model called `Api.User` the file `Application/Models/Api/User.php` will be loaded.

The `createInstance` parameter defines whether the model handler has to create an instance of your Model to store in the model handler. You can set this to false if you use models who are completely static but you don't have to.

###Manually loading a Controller
Manually loading a controller is almost identical to manually loading a model. Just call the `getControllerHandler()` on your app and call the `loadController()` method right after. The parameters are almost the same, the only difference is an extra `data` parameter in between `controllerName` and `namespace`. This defaults to an empty array and this is the data you want to pass to the controller.

###Manually loading a View
Using the app's view handler `getViewHandler()` you can also choose to manually load your view. The `loadView()` method accepts 4 parameters at most, being the view name, the data to pass, the version to load and lastly you can also pass the view class. The view class should only be set if you don't want to use the default `View` object located in `Application/Objects`. The parameter allows you to use your own class instead but make sure you know what you are doing!
