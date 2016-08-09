# Configuration

## Introduction
Several configuration steps need to be taken to make the framework more secure and work correctly. If you don't have access to `ssh` or a terminal you can also manually set these values in `Application/Config/App/configuration.json`, `Application/Config/App/directories.json` and `Application/Config/Sessions/sessions.json`

## Application keys
It is important to set the application keys as they are used to secure and encrypt your sessions and cookies. (If you use the `Cookie` and `Session` classes of xTend, otherwise your values wont be encrypted). To set these keys you can run the `init` command of xTend's workbench or you can manually set the keys in `Application/Config/Sessions/sessions.json`.
```
php workbench init
```

## General configuration
There are 5 general configuration variables, these are `location`, `timezone`, `environment`, `backupInterval`, `backupLimit` and `logLimit`. You can manually set these in the `Application/Config/App/configuration.json` file or you can use the `config` command of the workbench.
```
php workbench config location /
```

### What are these variables?
* The location variable defines the path where xTend is running from under your domain. By default it is `/` but if you want xTend to run under `/my-site` you can set it to this location.
* The timezone variable defines the timezone of your framework and is `UTC` by default.
* The backupInterval defines when xTend should take a backup. By default it is `1 week` but you can also set it to `false` if you want to disable this feature.
* The backupLimit defines the amount of backups should be kept. When the limit is reached xTend will automatically remove the oldest backup.
* The logLimit deifnes the amount of logs should be stored.

## Change the public directory
Lastly you can change the public directory. If you don't intend on using the workbench you can just change it manually (if you happen to have done this but want to go back just rename it again or change the workbench configuration value in `.workbench`). If you do intend on using the command line tool you can use the `set:public` command to rename the directory.
```
php workbench set:public public_html
```