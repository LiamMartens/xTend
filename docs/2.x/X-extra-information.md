# Extra information
## Creating a new app
You can use the `workbench` to create a new application under a new namespace. Just use the `new` command as follows
```
php workbench new Blog blog.domain.com *
```

In this case the command will create a new application in a directory called `Blog`. The application will receive the namespace `Blog` and will be launched when the user browses to `blog.domain.com` (the second parameter). You can also choose to enter a `any` to not specify the domain name. The third parameter is a path to match. In this case we match all (`any`) because we want the application to match all URL's prefixed with the `blog.domain.com` domain but since we can also choose to run several applications on 1 domain, we could use the path to specify the application.

*Notice should only be used when creating complex systems and they aren't very useful when you are using xTend to run a small website. Also don't forget to switch to your new application using the workbench's `set:application` command and then run the `init` command to initialize the app*

## Manually loading a Model
To manually load a model you have use the `load()` method of the `ModelHandler`.  

The `load()` method accepts 2 parameters at most being, the name of the model and the namespace (the application's namespace by default).

Keep in mind the name of the model includes the directory as well. For example, when you load the model called `Api.User` the file `Application/Models/Api/User.php` will be loaded.

## Manually loading a Controller
Manually loading a controller is almost identical to manually loading a model. Just call the `load()` method on the `ControllerHandler`. The parameters are almost the same, the only difference is an extra `data` parameter in between `controllerName` and `namespace`. This defaults to an empty array and this is the data you want to pass to the controller.

## Manually loading a View
You can also choose to manually load your view using the `load()` of the `ViewHandler`. The method accepts 4 parameters at most, being the view name, the data to pass, the version to load and lastly you can also pass the view class. The view class should only be set if you don't want to use the default `View` object located in `Application/Objects`. The parameter allows you to use your own class instead but make sure you know what you are doing.