#UrlHandler
As noted before you can use the `UrlHandler` to get your URL variable, but there is some other useful methods for you to use as well.

###getRoute
Use the `getRoute()` method to get the currently loaded route.

###getRequest
Use `getRequest()` to get the user's URL request.

###getMethod
Use `getMethod()` returns the HTTP verb that has been requested.

###setContentType
The `setContentType` is a wrapper for `header('Content-Type', '')`. It accepts solely 1 parameter being the content type though you can also pass in an extension instead of the full MIME type. There some extensions registered by default in the `Config/UrlHandler/ContentTypes.php` file.

###to
The `to` method accepts a route alias or a route object, url parameters and data to pass. The passed parameters will be used to fill in handles who use URL variables and the passed data is retrievable using the `data()` method of the `RequestDataHandler`. (kind of like using `GET` or `POST` data)

###navigate
Use `navigate()` to navigate to a URL (it's a wrapper for `header('Location: /')`). However the `navigate()` method automatically prefixes the application's URL and you can even pass in a `Route` object instead of a simple string. Besides the route or path you can also pass some data to the `navigate` using the second parameter. You can retrieve the data just like you would when using the `to()` method.
