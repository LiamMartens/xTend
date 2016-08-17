# Views
## Creating a simple view
Creating a view is as simple as creating a `.php` file in your application's `Views` directory or in a subdirectory of it.

## View data
As mentioned before you can pass data to either your view or your controller. To retrieve data you can fetch the view object, `Core\ViewHandler::get('optional_name')` and use the `get()` method on it.

<br><br><br><br>

# Templating
xTend has a simple yet extendable templating engine called `WOW`. By default the engine comes in the `COMBINED` templating flavor which combines it's 2 main flavors, `HTML` and `AT_SIGN`. You can set the flavor using the `workbench` or you can edit the `Config/Wow/Flavor.php` file.
```
php workbench wow:flavor HTML
```

## Creating a Wow view
To create a templated view you have to give your file a the double extension `.wow.php`. Furthermore you have to add following 2 wow expressions to the view
```
<version value="1" />
<compile value="change+version" />
```
Or using `AT_SIGN` flavor
```
@version:1
@compile:change+compile
```

These expressions define the version of the view, views are compiled with a version number to keep old views intact if something goes wrong. The second flag defines when to compile the view, you have several options to choose from `always`, `never`, `change`, `version` and `change+version`. The `change+version` will compile when the version goes up and the file has been changed. The other flags speak for themselves.

*Notice you can also create a wow view using the workbench's `new:view` command*

## Using a layout
Besides making your views more powerful you can also create layouts for your views to extend. To do so create a `Wow` file in your application's `Layouts` directory and start writing your layout. In a layout you can use all registered `Wow` expressions but you can also use a layout specific one called `section`.

```
<html>
<head>
    <section name="head" />
</head>
<body>
    <section name="body" />
</body>
</html>
```

*Notice the section keyword will change to `@section:NameOfSection` instead of `<section name="NameOfSection" />` when using the `AT_SIGN` flavor*

### Assigning the layout to the view
To tell `wow` to compile your view with a layout you have to use the layout flag as follows
```
<version value="1" />
<compile value="change+version" />
<layout value="nameOfLayout" />
```

*Notice as per usual when you use the `AT_SIGN` flavor, `<layout value="nameOfLayout" />` becomes `@layout:nameOfLayout`*

## Also, modules!
Aside from layout support, xTend's `WOW` engine also has modules. These are supposed to be used as components which can easily be plugged into your view. Just create your `WOW` module in your application's `Modules` directory and use the `module` statements.
```
<module name="mymodule" />
```
Or
```
@module:mymodule
```

## What else can I do with WOW
By default there's a bunch of expressions you can use in your views. As already saw the `WOW` engine comes in 2 flavors and we will go over every expression in each flavor.

### Statment injection
use the `{{  }}` expression, blade-like, to echo out a PHP statement or variable.
```
{{ $variable }}
```
*Notice `{{}}` is available in both flavors*

### echo
Use the `echo` expression to echo out a PHP statement or variable
```
<echo>$variable</echo>
```
OR
```
@echo:$variable;
```

### php
Use the `php` expression to insert blocks of PHP code if you need it
```
<php>
    //your PHP code
</php>
```
OR
```
@php:
    //your PHP code
@endphp
```

### if
Use the `if` expression to insert an if statement into your view or layout
```
<if>
    <condition>true</condition>
    //your code
    //PHP tag is closed here so re-opening PHP is necessary
</if>
```
OR
```
@if:true
    //your code
@end
```

### elseif
Use the `elseif` inside an `if` statement to add an elseif statement.
```
<if>
    <condition>false</condition>
    //your if code
<elseif>
    <condition>true</condition>
    //your elseif code
</if>
```
OR
```
@if:false
    //your if code
@elseif:true
    //your elseif code
@end
```

### else
Use `else` inside an `if` expression to include an else statement in your code
```
<if>
    <condition>false</condition>
    //your if code
<else>
    //your else code
</if>
```
OR
```
@if:false
    //your if code
@else
    //your else code
@end
```

### for
Use the `for` expression to add a for loop to your view
```
<for>
    <loop>$i=0;$i<10;++$i</loop>
    //your code
</for>
```
OR
```
@for:$i=0;$i<10;++$i
    //your code
@end
```

### foreach
Use the `foreach` expression to add a foreach loop to your view
```
<foreach>
    <loop>$a as $b</loop>
    //your code
</foreach>
```
OR
```
@foreach:$a as $b
    //your code
@end
```

### while
Use the `while` statement to add a while loop to your code
```
<while>
    <condition>$i<10</condition>
    //your code
</while>
```
OR
```
@while:$i<10
    //your code
@end
```

### css
Use this to add a easily add a css link
```
<css href="file.css" />
OR
<css>file.css</css>
```
OR
```
@css:file.css
```

### css embed
Use this to embed a css file into your code. This could potentially improve the speed of your site.
```
<css embed="file.css" />
OR
<css embed>file.css />
```
OR
```
@css_embed:file.css
```

### css external embed
Use this to embed an external css file into your code (from a CDN for example).
```
<css external-embed="http://....css" />
OR
<css external-embed>http://....css</css>
```
OR
```
@css_external:http://....css
```

### js
Use this to add a JavaScript file to your view
```
<js src="file.js" />
OR
<js>file.js</js>
```
OR
```
@js:file.js
```

### js embed
Use this to embed a JavaScript file into your view
```
<js embed="file.js" />
OR
<js embed>file.js</js>
```
OR
```
@js_embed:file.js
```

### js external embed
Use this to embed an external JavaScript file into your view
```
<js external-embed="http://......js" />
OR
<js external-embed>http://......js</js>
```
OR
```
@js_external:http://......js
```

### url
Use this to inject the application's url setting.
```
<url />
```
OR
```
@url
```

*Notice can be used in combination with, for example, the `js` expression. `<js><url/>/file.js</js>`*

### app
Use this to execute or retrieve something from your current app.
```
<app>getUrl()</app>
```
OR
```
@app:getUrl()
```

### app inject
Use this to inject an app method. This will compile without opening and closing php tags and without ending semicolon (;)
```
<app inject>getUrl()</app>
```
OR
```
@iapp:getUrl()
```
*All inject type expressions are compiled without opening and closing php tags and without ending semicolon (;)*

### controller
Use this to execute a controller method.
```
<controller>myMethod()</controller>
```
OR
```
@controller:myMethod()
```

*Notice every view also comes with a variable called $controller and another one called $controllers. The $controller contains your first controller and the $controllers variable contains all of em*

### controller inject
Use this to inject a controller method (for use inside an `if` statement for example)
```
<controller inject>myMethod()</controller>
```
OR
```
@icontroller:myMethod()
```

### controller with name
Use this to execute a specific controller's method (or retrieve a variable). This is intended to be used when loading multiple controllers.
```
<controller name="Pages.HomeController">myMethod()</controller>
```
OR
```
@controller_Pages.HomeController:myMethod()
```
*Use the name you used to load the controller*

### controller with name inject
Use this to inject a controller method from a specific controller.
```
<controller inject name="Pages.HomeController">myMethod()</controller>
```
OR
```
@icontroller_Pages.HomeController:myMethod()
```

### view
Use this to access the view object.
```
<view>my_data</view>
```
OR
```
@view:my_data
```

###view inject
```
<view inject>my_data</view>
```
OR
```
@iview:my_data
```

###view with name
```
<view name="index">my_data</view>
```
OR
```
@view_index:my_data
```

###view injet with name
```
<view inject name="index">my_data</view>
```
OR
```
@iview_index:my_data
```

### Form method spoof
Use this to spoof a form method (for use with DELETE or PUT)
```
<spoof method="DELETE" />
```
OR
```
@spoof_method:DELETE
```

### CSRF token
Use the csrf (or form) token expression to add a token to your form. You can read more about the formtoken handler in a later chapter.
```
<formtoken name="form-login" />
```
OR
```
@formtoken:form-login
```

These statements will be replaced by an `input` field like so:
```
<input type="hidden" name="token-form-login" value="your-token" />
```

### Persistent token
Use this to generate a persistent CSRF token as expained in the `FormTokenHandler` documentation.
```
<formtoken persistent name="form-login" />
```
OR
```
@formtoken_persistent:form-login
```

## Creating new expressions
If you take a look into the application's `Config/Wow/Wow.php` file you can see how most of the expressions are registered. Creating a new expression is as simple as recreating the statements in the config file. As long as you understand regular expressions it should be as easy as pie.
