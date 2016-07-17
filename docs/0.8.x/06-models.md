#Models
In MVC the Models are used to handle most of your data retrieval. xTend has simple Model support for you to use with or without database interaction.

###Creating a Model
To create a model you can either choose to create a file manually or use the `workbench`. If you use the workbench you'll just have to call the `new:model` command and it will create it for you with some default model code. If not you'll have create the file and add the code yourself. This is how a default model looks like
```
    namespace Application;
    class MyModel {
        class User extends \xTend\Blueprints\BaseModel {

        }
    }
```

As you can see the model resides in your application's namespace. This is not mandatory, but if you choose to use a different namespace you'll have specify this in your routes as well. Secondly, it is not necessary to extend the `BaseModel` class as this solely adds a constructor. You can look in the `Blueprints` directory to see the code.

###Retrieving your model
To retrieve your model you can use your application's `ModelHandler`. Just retrieve the handler from your app with the `getModelHandler()` method and call the `getModel()` function to get your currently loaded model. If you have only 1 model you don't have to pass any parameters, otherwise you'll have to specify which model you want. This will be the same name as you have used in your route.

```
    $app->getModelHandler()->getModel('\OtherNamespace\MyModel');
```
