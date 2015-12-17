#Backing up your site
##Auto-Backup
Auto-Backup is set to every day by default. You will see your backups show up in the `/System/Backups` directory. To turn it off change your `/System/Config/Core.php` and set `Backup` to `false`. You can also change the `Backup` property to a different time interval like `1 week` or `1 month` and so on.
##Manual Backup
If you want to take a manual backup use the following code:
```
xTend\Backup::Save();
```
##Restore a backup
If you want to restore the last backup use:
```
xTend\Backup::Restore();
```
You can also specify which back up you want to load by setting it's name like this:
```
xTend\Backup::Restore("1422018853-2015_01_23_13_14_13");
```
In this example the is backup with filename `1422018853-2015_01_23_13_14_13.zip`, created on 23 january 2015 on 13:14:13 UTC is restored.
##Backup Limit
There is a `BackupLimit` option in the `/System/Config/Core.php` file. This will limit the number of backups saved and remove the oldest backups when the limit is exceeded.