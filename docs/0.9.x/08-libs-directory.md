#The Libs directory
By default all files in your `Libs` directory are included (as well as all it's subdirectories). However, you can control this by adding certain files to your directories.

###Exclude
If you add a file called `.exclude` to a directory, xTend will completely ignore this directory and subdirectories. This can be useful for libraries where you need to load an `autoload.php` file and don't want to load everything else manually.

###Ignore
Add a `.ignore` file to a directory to specifically tell xTend to ignore certain files. Just put the files you want to ignore in the `.ignore` file (one each line).

####Example
```
file1.php
file2.php
```

###Order
Use a `.order` file to control the order of the file inclusion. The files you put in this file will be loaded first and in their respective order.
