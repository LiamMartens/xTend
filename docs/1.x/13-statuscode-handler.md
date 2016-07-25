#Status Code Handler
xTend has a central location to register all your status codes. This makes it easier to manage them in a large application.
You can also check all your status codes if you turn `DevelopmentStatus` on and navigate to your application in the browser with the `/codes` url. (This is defined in the `Config/Routes/DevelopmentRoutes.php` file).
*Notice it's suggested to register all status codes in your `Config` or `Libs` as these are included at all times. If you register them in a controller or a model xTend won't be able to display all your registered statuscodes as not all controllers or models are included at all times*

To get your application's status code handler you can call `getStatusCodeHandler()` on your application. This will return the current status code handler where you can register your codes or find already registered ones.

###Registering an status code
Use the `registerStatusCode()` method on your status code handler to register a new errorcode. The method accepts a maximum of 3 parameters being the code, a name and a human name. The code and the name should never change when you register an status code for your own convenience, the human name is technically the description of your status code.

####Example
```
    $app->getStatusCodeHandler()->registerStatusCode(0x0005, 'code:error', 'An error occured');
```

###Fetching an status code
You can use the `findStatus()` method to get an existing status code by either it's name or it's code. This will return an `StatusCode` object from which you can get it's readable name, the status code as exception, the status code in hexadecimal and the name and code as well.

```
    $code = $app->getStatusCodeHandler()->findStatus(5);
    //OR
    $code = $app->getStatusCodeHandler()->findStatus('code:error');
```

###StatusCode object methods

####getCode
Use `getCode()` to get the code of the errorcode.

####getHexCode
Use `getHexCode()` to get the code as hex.

####getName
Use `getName()` to get the name of the error.

####getReadableName
Use `getReadableName()` to get the human name of your status code.

####getStatus
Use `getStatus()` to get a full description of your error. This is a combination of the hex code, the name and the readable name.

####getException
Use `getException()` to get an `Exception` object for your status code. (If you're into throwing exceptions).
