# Navigating
You can use your app to navigate to certain handles or url's by using either the `to()` or the `navigate()` method. Both have their own use.

## to
You can use the `to()` method to navigate to route handles. You can pass between 1 to 3 parameters to the method. The first one is the URL handle, you can also pass a route to it when you get it from the router for example, the second one are the parameters you want to fill in, for URL variables, and the third one is the data you want to pass (we've covered getting the navigation data in the section about the `Request` object)
```
namespace Application;
use Application\Core\App;

App::to('user/{user}', [
    'user' => 'username'
], [
    'my_data'
]);
```

## navigate
You can use the `navigate()` method to navigate to simple urls. You can pass the url, navigation data and a boolean as parameters. The boolean specifies whether to include the current domain url or not (by default it's set to true).
```
namespace Application;
use Application\Core\App;

App::navigate('http://google.com', [], false);
// don't include the url here because we are navigating to a different domain

App::navigate('contact', []);
// do include the url here because we are navigating to an inner url
```