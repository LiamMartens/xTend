#Request data handler
On some webservers the magic PHP variables `$_GET` and `$_POST` aren't set correctly. To make this work xTend has a little helper to get you on your way if you happen to encounter this.

###Using the request data handler
Use the `getRequestDataHandler()` method of your application to get the handler. The handler only has 3 methods you'll need being `get()`, `post()` and `data()`. These first 2 will return the GET and POST variables respectively, even if your webserver didn't handle them correctly and the last one will return data passed by the UrlHandler's `navigate` method.
