#The Route Object
Some more info on the route object

##Creating a Route
Creating a route is as simple as calling  
`new Route(handle,route,alias)`

##The Parameters
###Handle
The handle is the URL or error the route object is related to.
This can be a `string` or an error code (hex or decimal // take a look in the `ErrorCodeHandler` section).

###Route
The route defines what to execute when a match is found.  
You can find more on this subject [here](http://xtend.readthedocs.org/en/latest/Routes/) (Section: Routes - What to do when a route is matched)

###Alias
The alias is the name you want to give to the route object.
This way you can easily identify and load other routes.

##Methods
###Handle / GetHandle
To set and get the handle

###Route / GetRoute
To set and get the route

###Alias / GetAlias
To set and get the alias

###Data / GetData
To set and get data passed to the route.
A little more on route data passing [here](http://xtend.readthedocs.org/en/latest/Routes/) (Section: Routes - What to do when a route is matched)

###To
If the handle is a URL it will redirect to the given url
(not possible with regexed and parameterized urls nor with error handles)

###Load
The load function will load the route execution. 
This is very useful if you want to load another route.

###IsMatch
The IsMatch method checks whether the handle matches the URL.
The method expects 1 parameter being the requested path (URL).