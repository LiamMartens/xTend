#Routes
Read more about setting up routes.

##Route Types
* `Home` : This is the index or home route
* `AppError` : This one can be used to catch errors thrown by the framework
* `Post` : Used to catch POST requests
* `Get` : Used to catch GET requests
* `Any` : Used to catch any request (these are checked first)
* `Def` : This is a default route when no other is available

##URLs
* Normal URL. For example `hello` will catch `example.com/hello` and `hello/liam` will catch `example.com/hello/liam`
* URL variables. For example `hello/{name}` will catch `example.com/hello/{anything-can-be-here}` and there will be a variable available with the key from the url. You can retrieve URL variables by using the [URL helper class](http://xtend.readthedocs.org/en/latest/URL/)
* Regex urls. For example `hello/rx{^([a-zA-Z])$}` will catch `example.com/hello/{any-text-containing-letters-both-upper-and-lowercase}`. 
* Regex URL variables. For example `hello/rx{name}{^([a-z])$}` will catch `example.com/hello/{any-text-containing-lowercase-letters}` and there will be a variable available with the key from the url, in this case `name`. ou can retrieve URL variables by using the [URL helper class](http://xtend.readthedocs.org/en/latest/URL/)
* The `*` character will catch anything. Can be used like `hello/*` but this will not make a variable available.

##Routes - What to do when a route is matched?
There are 3 ways of defining this:
* `text/plain` : Plain text will echo out (not exactly useful except for maybe some testing purposes)
* `function` : A function which can be executed. You can load views, models and controllers using code. Using `Views::Initialize({name})`, `Controllers::Initialize({name})`, `Models::Initialize({name})` respectively.
* `array` : An array where you can define the View, the Controller and the Model. You can also pass data to the Controller, or the View if there is no Controller defined, by adding a `Data` key with an array as it's value.

##Restrict
Restrict can be used to execute a function before defining routes. For example, if you only want to allow IP address `127.0.0.1` onto a `GET` route called `hack` you can do the following:
```
Router::Restrict(function() {
    return ($_SERVER['REMOTE_ADDR']=="127.0.0.1") ? true : false;
}, function() {
    Router::Get("hack", "You hacked my website");
})
```
The `hack` route will now  only exist when IP `127.0.0.1` is visiting.

##Using AppError
You can set an AppError route as follows:
```
Router::AppError(Error::{ErrorType}, Route);
```
You can find the error types inside `/System/Core/Error.php`, but you can also defined new ones.

##Namespacing
When initializing views you can pass it's name but you can also pass it's subdirectories for easy namespacing of your views. For example, `just.foo.bar` will look for a view called `bar` in the directory `foo` which is a subdirectory of the directory `just`. The same goes for models and controllers.