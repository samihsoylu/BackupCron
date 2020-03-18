# BackupCron
BackupCron is a simple tool that periodically makes backups of your website and database. Initially designed for a WordPress website, you can expect it to work on any web server. To completely understand how this tool works, it is advised to look in to the code =)

The tool does the following:
* Back up a PHP project
* Back up a MySQL Database
* Uploads to webdav cloud environment

This tool is compatible with [TransIP Stack](https://www.transip.nl/stack/).

## Requirements
* Storage cloud environment that supports Webdav connection
* PHP 7.1 or later

## Getting started
This tool can be downloaded by visiting the [releases](https://github.com/samihsoylu/BackupCron/releases) page, or using git to clone.

Once downloaded and extracted, rename `.env.example` to `.env` and replace the dummy text within the environment file to your correct credentials.

For reference:
```
    [app][backups_dir] is the path to a temporary directory of your choice `/tmp/` would suffice
    [app][uploads_dir] path to the directory you wish to periodically back up

    [webdav] credentials to your cloud
    [webdav][sql_backup_dir] directory path, where to store your database on your cloud
    [webdav][uploads_backup_dir] directory path, where to store your files
    
    [stmp] your smtp credentials for sending emails
    
    [db] credentials to the database that you wish to periodically back up
    
    [debug] specify a "to" email address for debugging purposes
```

Once configured, you must set up a cron job that will periodically execute the `cron.php` file on your web server. The `cron.php` file creates backups and uploads them to your cloud.
