#UrlHandler
As noted before you can use the `UrlHandler` to get your URL variable, but there is some other useful methods for you to use as well.

###getRoute
Use the `getRoute()` method to get the currently loaded route.

###getRequest
Use `getRequest()` to get the user's URL request.

###getMethod
Use `getMethod()` returns the HTTP verb that has been requested.

###navigate
Use `navigate()` to navigate to a URL (it's a wrapper for `header('Location: /')`). However the `navigate()` method automatically prefixes the application's URL and you can even pass in a `Route` object instead of a simple string. Besides the route or path you can also pass some data to the `navigate` using the second parameter. You can retrieve this data again using the `data()` method in the `RequestDataHandler`.
