#Sessions and Cookies
More info on now to use the `Sessions` and the `Cookies` helper classes

##Sessions
###Set
The `Set` method expects exactly 2 parameters being the key and the value. It is called like `Sessions::Set('key','value');`. Session data stored using the helper class can't just be returned using `$_SESSION['key'];`. The key is hashed using `sha1` and the data is encrypted.
###Get
The `Get` methods expects the first parameter to be the key you want to return, the second parameter is the default value to return when the session key is not available and defaults to `false`. Thus, the second parameter is optional. ex. `Sessions::Get('key','default');`. The `Get` method will automatically decrypt the data.
###Remove
The `Remove` method solely expects 1 parameter which is the key to remove. Usage speaks for itself.

##Cookies
###Set
The `Set` method works exactly the same as the `Set` method for `Sessions` the only difference is you can add 2 more parameters. The first one is the expiration date, which will default to `time()+3600*24`, and the domain, which defaults to `'/'`;
###Get
The `Get` method is exactly the same as the `Sessions::Get` method
###Remove
The  `Remove` method can be supplied with 1 extra parameter being the domain. The domain parameter defaults to `'/'` if none is provided.