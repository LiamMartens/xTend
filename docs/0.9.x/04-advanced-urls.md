#Advanced URL's
In section 2 we went over all types of routes we can create
and while doing this we solely used some simple URL handles.
Right now we will go over the advanced URL handles.

###Named URL variable
First off you can catch a variable in the URL. The syntax is simple, just put the name of the variable you want between `{}`  in whatever section you want to catch the variable.

*Notice the router checks per section, which means it will split the handle in parts on the /*

####Example

```
    $app->getRouter()->get('user/{user}', function($app) {
        return $app->getUrlHandler()->getData('user');
    });
```

The above example will print out whatever the user enters in the URL bar after the `user/` path. So when you would browse to `domain.com/user/liam` you will see `liam` appear on the screen.

###Regex
We can also use a regex inside our URL handle to specify what can be entered in the URL bar. The syntax is similar to the Named URL variable (`rx{YOUR REGEX}`).

####Example
```
    $app->getRouter()->get('user/rx{[0-9]}', function($app) {
        return 'Hi there';
    });
```

In this example we would only be able to see `Hi there` when we browse to `user/` with any number between 0 and 9 afterwards.

###Named and regexed URL variable
This is a combination of the above and it's syntax is practically identical. Just combine both syntax like this `rx{user}{[0-9]}`.
