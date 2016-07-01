#FormToken handler
We already saw the FormToken handler earlier on in the `views and templating` section. Here you can find information about how to check and regenerate tokens.

###Getting the handler
Getting the handler is as easy is calling `getFormTokenHandler()` on your current application. This handler has 2 methods for you to use.

###Generating a token
To generate (or regenerate) a token you can use the handler's `generate()` method. The method accepts just 1 parameter being the name of the token and it returns the token you need to do the check.

```
    $token = $app->getFormTokenHandler()->generate('form.login');
```

###Checking a token
To check a token you can use the `check()` method. This method accepts 2 parameters being the name of your token (as you generated it before) and the value you got from for example your form or AJAX request (the name of the hidden CSRF input field will be the name of your token prefixed with `token-`. i.e, if you generate a token called `login`, the input will have the name of `token-login`). It will return `true` when the token is valid and `false` if not.
