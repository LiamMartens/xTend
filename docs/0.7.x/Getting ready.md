#xTend 0.7.0
##Getting ready for your first xTend website

After downloading or cloning the xTend repository you have to put it somewhere on your hosting or server.
Make sure you place the files located in the public directory (by default `www`) in your public directory and put the system files (by default in `System`) in a directory which is not publicly accessible. As you may have read in [Understanding the folder structure](/en\/atest/0.7.x/Understanding%20the%20folder%20structure) you can name these folders anything but don't forget to edit the `require_once` file in the `index.php`

###Example files
If you have already looked through the directories and files you might have noticed there are some example files in place at `\System\Controllers`, `\System\Layouts`, `\System\Models` and `\System\Views`. If you want you can read these small examples to get a little bit of a feel for it or you can just remove them. If you remove them you might also want to remove the automatically created backup placed in `\System\Backups`, the meta file in the `\System\Meta` directory, the PHP file in `\System\ViewOutput` and the log file in `\System\Logs`

###Folder permissions
To make xTend work to the fullest you will have to grant some permissions on a couple of directories.
* allow `write` on `\System\Backups`: Only if you keep auto-backup on
* allow `write` on `\System\Logs`: Otherwise xTend won't be able to write any logs
* allow `write` on `\System\Meta`: If you don't xTend won't be able to save any additonal file information in the meta files
* allow `write` on `\System\ViewOutput`: Only if you use the WOW templating engine. Compiled views need to be written to this directory.
* allow `write` on `\www\css`: If you want to use the Leafo SCSS compiler.
