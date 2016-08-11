# Status Code Handler
xTend has a central location to register all your status codes. This makes it easier to manage them in a large application.
You can also check all your status codes if you set your application's environment to `development` and navigate to your application in the browser with the `/xtend/codes` url. (This is defined in the `Config/Routes/Development.php` file).
*Notice it's suggested to register all status codes in your `Config` or `Libs` as these are included at all times. If you register them in a controller or a model xTend won't be able to display all your registered statuscodes as not all controllers or models are included at all times*

## Registering an status code
Use the `register()` method on your status code handler to register a new errorcode. The method accepts a maximum of 3 parameters being the code, a name and a human name. The code and the name should never change when you register an status code for your own convenience, the human name is technically the description of your status code.
```
namespace Application;
use Application\Core\StatusCodeHandler;

StatusCodeHandler::register(0x005, 'code:error', 'An error occured');
StatusCodeHandler::register(6, 'code:error2', 'Another error occured');
```

## Fetching a status code
You can use the `find()` method to get an existing status code by either it's name or it's code. This will return a `StatusCode` object from which you can get it's readable name, the status code as exception, the status code in hexadecimal and the name and code as well.
```
namespace Application;
use Application\Core\StatusCodeHandler;

$code = StatusCodeHandler::find(5);
$code = StatusCodeHandler::find(0x0005);
$code = StatusCodeHandler::find('code:error');
```

## The StatusCode object
### code
Use `code()` to get the code of the errorcode.

### hex
Use `hex()` to get the code as hexadecimal with preceeding `0x`.

### name
Use `name()` to get the name of the error.

### readable
Use `readable()` to get the human name of your status code.

### status
Use `getStatus()` to get a full description of your error. This is a combination of the hex code, the name and the readable name.

### Throwing an exception
Since the `StatusCode` object extends the `Exception` class you can `throw` any `StatusCode` to generate an error.