#Controllers
Controllers should be used to handle the data coming from your model as well as handling user requests. In xTend controllers are very simple and have almost no footprint to them just like the models.

###Controller data
In the section about `views and templating` you can read how to retrieve data from your view. If your data has been passed to the controller however you can retrieve it in a similar way. Just retrieve your controller and call `getData()` or use the arrow pointer.

###Creating a Controller
To create a controller you can use the workbench's `new:controller` command or you can manually create the file. The process practically identical to creating a model. By default your controller will look somethng like this
```
    namespace Application;
    class MyController extends \xTend\Blueprints\BaseDataController {

    }
```

*Notice as said in the models section you don't have to extend the blueprint, though with controllers you'll have to implement something in the lines of the `BaseDataExtension` to support passing data to your controller from the router*

*Notice a BaseController derived controller has 3 protected members by default. The `$_app`, the `$_models` and the `$_model`. The models member contains all loaded models by their registered name and the model member contains the first model*

###Creating a respond controller
You can also create a `BaseRespondController` using the `new:respondcontroller` method or by manually extending the `BaseRespondController`. This adds a `protected respond` method. The method accepts a maximum of 3 parameters being a success boolean, a status code or name and additional data. The `respond` method will return an array and set the content type to JSON. When you return the array yourself the controller handler will automatically `echo` it as JSON.

*Notice whenever you return an array in a controller method the controller handler will automatically create a JSON from the array and echo it*

###Retrieving your controller
Getting your controller in PHP is analog to retrieving a model. Call the `getControllerHandler()` from your app and use the `getController()` method next. Pass a parameter with the name of your controller if you have more than 1 loaded in.
