#Libs
Controlling the `/System/Libs` directory.
##Default
By default all files inside the directory are included, also files inside subdirectories are found and will be included.
##Exclude a complete directory
If you want to exclude a complete directory, just add a file to the directory named `.exclude` and the complete directory will be ignored.
##Exclude a file
If you want to exclude certain files add a `.ignore` file to the files' directory and add it's filename to the file. Example:
```
Library1.php
Library2.php
```
Now `Library1.php` and `Library2.php` will not be included automatically.
##Order inclusion
If you want to include a file before all other files inside a certain directory add a `.order` file and add it's filename to the file. For example:
```
Library1.php
Library2.php
```
Now `Library1.php` and `Library2.php` will be loaded first and in that order. Afetewards the other files are included.