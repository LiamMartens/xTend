#URL Helper class
##Route
Static method `URL::Route()` will return the key of the matched route currently displayed. For example if you have a route `person/{name}` defined and you navigate to `person/peter` the function will return `person/{name}`.
##Request
Static method `URL::Request()` will return the originally requested url (excluding domain and extension). For example when navigating to `http://example.com/person/peter` it will return `person/peter`.
##Method
Static method `URL::Method()` will return the request method. The method can return 3 values: `ANY`, `GET` and `POST`. These return values are self explanatory.
##URL variables
To retrieve URL variables you can call `URL::GetParameter({name-of-parameter})`. You can also call a static function with it's name, like this `URL::{name-of-parameter}()`. (Read about URL variables [here](http://xtend.readthedocs.org/en/latest/Routes/))