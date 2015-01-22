#Getting Started
A quick guide to getting your first simple pages set up using the Wow templating engine and normal routes.

##STEP 1: Installation
[Read about the installation here](http://xtend.readthedocs.org/en/latest/Installation/)

##STEP 2: Creating your first layout
Let's make our first layout for views to extend. In the following example it will be called `main`.
###Create the layout file
The first step is creating the file for the layout, we will call it `main.wow.php` and it will be located in the `/System/Layouts` directory.
###Editing the layout file
For the layout to actually be useful we should put some content into it. For now we will use it as a standard webpage format by adding following code to the file and saving it:
```
<!DOCTYPE html>
<html>
    <head>
        <meta charset="@charset">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@description">
        <meta name="keywords" content="@keywords">
        <meta name="author" content="@author">
        @css:css/normalize.css
        @section:Head
    </head>
    <body>
        @section:Body
    <body>
</html>
```
**So what does all of this mean?**  
The HTML itself should be pretty straightforward but what are all those `@` symbols doing there? Each one means something to the templating engine. the `@section:{name}` define sections which can be filled in by a view. Other examples are `@charset` which will translate to the charset variable stored in the core config and `@description` stored in the same configuration file (`/System/Config.php`). You can check these expressions in `/System/Config/Wow.php` and you can also add more expressions if you like.

##STEP 3: Creating your first view
Now we will create the view file.
###Create the view file
First off you'll need to create the file for the view. We will create the file inside `/System/Views` and name it `home.wow.php`. Of course you can give it a different name (`{name}.wow.php`).
###Editing the view file
The following code will be a simple example but should make sense pretty fast.
```
@version:1
@compile:change
@layout:main

@section:Head
    <title>Example page</title>
@endsection:Head

@section:Body
    Some stupid content
    (ノ^_^)ノ (ノ^_^)ノ (ノ^_^)ノ
@endsection:Body
```
**Some explanation please**  
In every Wow view, you'll need at least two attributes. These are `@version` and `@compile`. `@layout` is not actually required but it is advised to use layouts in order to make life easier. `@layout` is self-explanatory, but what are the others? The `@version` keyword tells the compiler which version of the view this is. The version numbered can be seen in the name of the compiled views inside `/System/ViewOutput`. The filename is formatted like this `{name-of-the-view}-{version-number}.php`. The `@compile` expression kind of works in conjunction with the `@version` statement. There are 4 compile options, these are `always`,`version`,`never` and `change`. When the `version` option is used the view will compile when there is no compiled view with the current version number, the others should be self explanatory.

##STEP 4: Creating the route
The last step is to tell the framework where to go. We will be using the `/System/Config/Routes.php` file to add routes but you could use a different file do define routes.
###Setting up the home route
We will use the home route as example. To add a home route add following code:
```
Router::Home(array(
    "View" => "home"
));
```
This is self explanatory but keep in mind there is much more you can do. To find out more you can look through the docs.
