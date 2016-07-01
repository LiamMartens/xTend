#Views and templating

###Views
Creating a view is as simple as creating a `.php` file in your application's `Views` directory or in a subdirectory of it.

###Templating
xTend has it's own simple and extendable templating engine called `WOW`. If you want your views to be compiled using the engine you have to create your files with a double extension being `.wow.php`. Furthermore you have to tell the engine when to compile the view and what version it is. (this is where the `version` option in the `Array of options` comes in). To do this we have to add following `WOW` expressions to the view
```
    <version value="1" />
    <compile value="always" />
```

*Notice you can also use the workbench's `new:view` command to create a new WOW view*

*Notice depending on your chosen WOW flavor the syntax changes. You can set the WOW flavor using the `wow:flavor` command in the workbench or you can change it manually in the `Config/Wow/Flavor.php` file. You have 2 different flavors to chose from being `HTML` (the default one) and `AT_SIGN`. When setting the flavor to `AT_SIGN`. Both the `version` and `compile` keywords will change into `@version:1` and `@compile:always` respectively.*

*Notice we used the `always` compile flag in the example but there is also `never`, `version`, `change` and `version+change`. `always` and `never` are self-explanatory. The `version` flag will compile when the version updates and the `change` flag will compile when the view, the layout or a module, more on that later, changes. The `change+version` flag combines the last two flags.*


###Using a WOW layout
Besides making your views more powerful you can also create layouts for your views to extend. To do so create a `WOW` file in your application's `Layouts` directory and start writing your layout. In a layout you can use all registered `WOW` keywords but you can also use a layout specific one called `section`.

*Notice the section keyword will change to `@section:NameOfSection` instead of `<section name="NameOfSection" />`*

####Example
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



####Extending the layout
Extending your layout is as simple as adding the `<layout value="layout" />` expression to your view. To fill in your layout sections you need to use the following statements
```
    <section name="NameOfSection">
        //your code
    </section>
```

###Also, modules!
Aside from layout support, xTend's `WOW` engine also has modules. These are supposed to be used as components which can easily be plugged into your view. Just create your `WOW` module in your application's `Modules` directory and use the `module` statements.
```
    <module name="mymodule" />
```
OR
```
    @module:mymodule
```


###What else can I do with WOW
By default there's a bunch of expressions you can use in your views. As already saw the `WOW` engine comes in 2 flavors and we will go over every expression in each flavor.

####echo
Use the `echo` expression to echo out a PHP statement or variable
```
    <echo>$variable</echo>
```
OR
```
    @echo:$variable
```

####php
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

####if
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

####elseif
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

####else
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

####for
Use the `for` expression to add a for loop to your view
```
    <for>
        <loop>$i=0;$i<10;$i++</loop>
        //your code
    </for>
```
OR
```
    @for:$i=0;$i<10;$i++
        //your code
    @end
```

####foreach
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

####while
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

####css
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

####css embed
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

####css external embed
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

####js
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

####js embed
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

####js external embed
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

####url
Use this to inject the application's url setting.
```
    <url />
```
OR
```
    @url
```

*Notice can be used in combination with, for example, the `js` expression. `<js><url/>/file.js</js>`*

####app
Use this to execute or retrieve something from your current app.
```
    <app>getUrl()</app>
```
OR
```
    @app:getUrl()
```
*Notice you can also use PHP to retrieve the app in your view as every view is compiled in your application's namespace with a variable called `$app` containg your current application.*

####app inject
Use this to inject an app method. This will compile without opening and closing php tags and without ending semicolon (;)
```
    <app inject>getUrl()</app>
```
OR
```
    @iapp:getUrl()
```
*All inject type expressions are compiled without opening and closing php tags and without ending semicolon (;)*

####controller
Use this to execute a controller method.
```
    <controller>myMethod()</controller>
```
OR
```
    @controller:myMethod()
```

####controller inject
Use this to inject a controller method (for use inside an `if` statement for example)
```
    <controller inject>myMethod()</controller>
```
OR
```
    @icontroller:myMethod()
```

####controller with name
Use this to execute a specific controller's method (or retrieve a variable). This is intended to be used when loading multiple controllers.
```
    <controller name="Pages.HomeController">myMethod()</controller>
```
OR
```
    @controlle_Pages.HomeController:myMethod()
```

####controller with name inject
Use this to inject a controller method from a specific controller.
```
    <controller inject name="Pages.HomeController">myMethod()</controller>
```
OR
```
    @icontroller_Pages.HomeController:myMethod()
```

####CSRF token
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

###Creating new expressions
If you take a look into the application's `Config/Wow/Wow.php` file you can see how most of the expressions are registered. Creating a new expression is as simple as recreating the statements in the config file. As long as you understand regular expressions it should be as easy as pie.
