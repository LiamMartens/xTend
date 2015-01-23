#Configure
##PreConfigure
Before loading `/System/Config` all previously included classes, from `/System/Libs`, `/System/Blueprints`, `/System/Objects`, will be checked for a static method called `PreConfigure`. If it exists it will be executed.
##PostConfigure
After loading `/System/Config` all classes are looped once again and this time checked for a static `PostConfigure` method. Again, if the method exists, it will be executed. An example of `PostConfigure` is the router. After including the configuration, which contains the route definitions, this method is called from the Router class which will then match the url and direct accordingly.