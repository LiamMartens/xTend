#CLI (Command Line Interface)
You can only run these commands when you're current working directory is an xTend directory. This is the parent directory of the `System` folder.

##Creating a backup
```
php xtend backup [name of your public directory]
```

##Restoring a backup
```
php xtend restore [name of your public directory]
```
This will NOT remove the last backup, just restore it.

##Upgrading xTend System
```
php xtend upgrade [name of your public directory]
```