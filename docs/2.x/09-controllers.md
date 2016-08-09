# Controllers
Controllers should be used to handle the data coming from your model as well as handling user requests. In xTend controllers are very simple and have almost no footprint to them.

## Controller data
In the section about `views and templating` you can read how to retrieve data from your view. If your data has been passed to the controller however you can retrieve it in a similar way. Just call the static `get` method on your controlller class.

## Creating a Controller
To create a controller you can use the workbench's `new:controller` command or you can manually create the file. The process practically identical to creating a model. By default your controller will extend the `StaticDataExtension` class to allow the data to be passed to the controller, but you don't have to extend it and you can also implement your own `set` and `get` methods to support passing data.

## Creating a respond controller
You can also create a `RespondController` using the `new:respondcontroller` method or by manually extending the `RespondController`. This adds a static `respond` method. The method accepts a maximum of 3 parameters being a success boolean, a status code or name and additional data. The `respond` method will return an array and set the content type to JSON. When you return the array in your own controller method, the application will automatically `echo` it as JSON.

*Notice whenever you return an array in a controller method the controller handler will automatically create a JSON from the array and echo it*