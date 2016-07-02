#Files and directories
xTend has both a `FileHandler` and a `DirectoryHandler` to handle your files smartly.

###FileHandler
To get your application's file handler you have to call the `getFileHandler()` method on your app. This will return the current file handler which you can use to retrieve files as `File` objects. The object can then be used to interact with the file itself. Below are all available methods.

*Notice the `File` object has a toString defined to make it return the full path which means you'll be able to use it inside of strings for example*

####system
Use the `system()` function to get a file from your `Application` directory. You can use the `.` notation if you like and you can also specify how many parts of the path are considered extension.
*This is a FileHandler method*
```
    $file = $app->getFileHandler()->system('Config.Routes.Routes.php');
    //you can also specify the number of extension parts, for example
    $file = $app->getFileHandler()->system('Views.home.wow.php', 2);
```

####public
Use the `public()` function to get a file from your `public` directory. The usage is analog to the `system()` method.
*This is a FileHandler method*
```
    $file = $app->getFileHandler()->public('css.style.css');
```

*Only File methods from here*

####exists
Returns a `true` if the file exists, returns `false` otherwise.
```
    $file->exists();
```

####writable
Returns `true` if the file is writable. Returns `false` if not.
```
    $file->writable();
```

####name
Returns the filename without the full path.
```
    $file->name();
```

####parent
Returns the owning directory as a `Directory` object.
```
    $file->parent();
```

####move
Moves the file to the specified destination.
```
    $file->move('destination.filename.php');
```

####copy
Copies the file to the specified destination.
```
    $file->copy('destination.filename.php');
```

####remove
Use the `remove()` method to delete the file.
```
    $file->remove();
```

####read
The `read()` function will return the file contents.
```
    $file->read();
```

####write
Use `write()` to write contents to a file.
*This will remove existing file contents*
```
    $file->write('content');
```

####append
Use `append()` to write to a file and keep existing file contents.
```
    $file->append('content');
```

####setMeta
You can use the `setMeta()` method to assign meta information to your file.
*The meta information is kept in the `Meta` directory. WOW uses this for example to keep compile times*
```
    $file->setMeta('key', 'value');
```

####getMeta
You can use the `getMeta()` function to get a meta value. You can also pass a default value to return when the meta key isn't found.
```
    $file->getMeta('key', 'default');
```

####include
Use the `include()` method to include the file in your project.
```
    $file->include();
```

####extension
Use the `extension()` method to get the extension part of the file. (doesn't include the `.`)
```
    $file->extension();
```


###DirectoryHandler
To get the your application's directory handler you have to call the `getDirectoryHandler()` method on your app. This will make the handler available for you to use. The `DirectoryHandler` itself will be used to retrieve a certain directory but the interaction with it will happen through a `Directory` object (`xTend\Core\DirectoryHandler\Directory`). Below are all available methods.

*Notice the `Directory` object also has a toString defined to make it return the full path.

####system
Use the `system()` function to get a directory from your `Application`. You can use `.` notation if you like.
*This is a DirectoryHandler method*
```
    $directory = $app->getDirectoryHandler()->system('Config.Routes');
```

This will return a `Directory` object containg your `Application/Config/Routes` directory.

####public
Use the `public()` function to get a file from your `public` directory. You can use the `.` notation if you like.
*This is a DirectoryHandler method*
```
    $directory = $app->getDirectoryHandler()->public('assets.js');
```

*Only Directory methods from here*

####exists
Returns `true` or `false` depending on whether the directory exists.
```
    $directory->exists();
```

####writable
Returns `true` or `false` depending on whether the directory is writable.
```
    $directory->writable();
```

####name
Returns the name of the directory, omitting the full path.
```
    $directory->name();
```

####parent
Returns the parent directory as a `Directory` object.
```
    $directory->parent();
```

####scan
Returns all files and directories as either a `File` or `Directory` object. Optionally you can pass a `bool` parameter to make xTend look recursively or not.
```
    $directory->scan(true);
```

####files
Returns all the files in your directory as `File` objects. Optionally pass a parameter to perform a recursive search.
```
    $directory->files(false);
```

####directories
Returns all the directories as `Directory` object. Optionally pass a parameter to perform a recursive search.
```
    $directory->directories(false);
```

####create
Use the `create` method to create the directory if it doesn't exist.
```
    $directory->create();
```

####move
Use `move` to rename a directory
```
    $directory->move($destination);
```

####copy
Use `copy` to copy a directory
```
    $directory->copy($destination);
```

####remove
Use `remove` to delete a directory
```
    $directory->remove();
```

####file
Use the `file` method to retrieve a `File` object of a specific file in your directory.
```
    $directory->file('my.file.php');
```

####directory
Use the `directory` method to retrieve a `Directory` object of a specific directory in your current folder.
```
    $directory->directory('my.directory');
```
