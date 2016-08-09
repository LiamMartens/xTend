# File helper
xTend has a file helper called `FileHandler` which  you can use to retrieve and interact with files.

## FileHandler methods
### system
Use the `system()` function to get a file from your `Application` directory. You can use the `.` notation if you like and you can also specify how many parts of the path are considered extension.
```
namespace Application;
use Application\Core\FileHandler;

$file = FileHandler::system('Config.Routes.Routes.php');
//you can also specify the number of extension parts, for example
$file = FileHandler::system('Views.home.wow.php', 2);
```

### public
Use the `public()` function to get a file from your `public` directory. The usage is analog to the `system()` method.
```
$file = FileHandler::public('css.style.css');
```

Both the `system` and the `public` method return a `File` object which has his own set of methods.

*Notice when you cast the `File` object to a string it will return it's absolute path*

## The File object
### exists
Returns `true` if the file exists, returns `false` otherwise.
```
$file->exists();
```

### writable
Returns `true` if the file is writable. Returns `false` if not.
```
$file->writable();
```

#### name
Returns the filename without the full path.
```
$file->name();
```

### parent
Returns the owning directory as a `Directory` object.
```
$file->parent();
```
*Notice read about the `Directory` object in the next chapter*

### move
Moves the file to the specified destination.
```
$file->move('destination.filename.php');
```

### copy
Copies the file to the specified destination.
```
$file->copy('destination.filename.php');
```

### remove
Use the `remove()` method to delete the file.
```
$file->remove();
```

### read
The `read()` function will return the file contents.
```
$file->read();
```

### write
Use `write()` to write contents to a file.
```
$file->write('content');
```
*Notice this will overwrite the existing contents*

### append
Use `append()` to write to a file and keep existing file contents.
```
$file->append('content');
```

### meta
You can use the `meta()` method to assign, retrieve or remove meta information associated to the file. The method accepts a maximum of 3 parameters, the first one is the key, the second one is the value and the third one is a boolean to tell xTend to remove the meta value.
```
// setting a value
$file->meta('key', 'value');

// retrieving a value
// returns false if the key was not found
$file->meta('key');

// removing a value
$file->meta('key', null, true);
```
*Notice the meta information is kept in the `Meta` directory. Wow uses meta information to keep compile times for example*

### include
Use the `include()` method to include the file in your project.
```
$file->include();
```

### extension
Use the `extension()` method to get the extension part of the file. (doesn't include the `.`)
```
$file->extension();
```