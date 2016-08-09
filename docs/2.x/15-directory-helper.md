# Directory helper
We previously covered the file helper but there is also a directory helper called `DirectoryHandler` which you can use to retrieve and interact with directories.

## DirectoryHandler methods
### system
Use the `system()` function to get a directory from your `Application`. You can use `.` notation if you like.
```
namespace Application;
use Application\Core\DirectoryHandler;

$directory = DirectoryHandler::system('Config.Routes');
```

### public
Use the `public()` function to get a file from your `public` directory. You can use the `.` notation if you like.
```
$directory = DirectoryHandler::public('assets.js');
```

Both the `system` and the `public` method return a `Directory` object which has his own set of methods.

*Notice when you cast the `Directory` object to a string it will return it's absolute path*

### exists
Returns `true` or `false` depending on whether the directory exists.
```
$directory->exists();
```

### writable
Returns `true` or `false` depending on whether the directory is writable.
```
$directory->writable();
```

### name
Returns the name of the directory, omitting the full path.
```
$directory->name();
```

### parent
Returns the parent directory as a `Directory` object.
```
$directory->parent();
```

### scan
Returns all files and directories as either a `File` or `Directory` object. Optionally you can pass a `bool` parameter to make xTend look recursively or not.
```
$directory->scan(true);
```

### files
Returns all the files in your directory as `File` objects. Optionally pass a parameter to perform a recursive search.
```
$directory->files(false);
```

### directories
Returns all the directories as `Directory` object. Optionally pass a parameter to perform a recursive search.
```
$directory->directories(false);
```

### create
Use the `create` method to create the directory if it doesn't exist.
```
$directory->create();
```

### move
Use `move` to rename a directory
```
$directory->move($destination);
```

### copy
Use `copy` to copy a directory
```
$directory->copy($destination);
```

### remove
Use `remove` to delete a directory
```
$directory->remove();
```

### file
Use the `file` method to retrieve a `File` object of a specific file in your directory.
```
$directory->file('my.file.php');
```

### directory
Use the `directory` method to retrieve a `Directory` object of a specific directory in your current folder.
```
$directory->directory('my.directory');
```
