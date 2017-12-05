<?php

# Dependencies (Composer auto loader)
require dirname(__FILE__).'/vendor/autoload.php';

# Imports all classes.
require dirname(__FILE__).'/php/init.php';

# Directory configurations
$backup_dir = '/home/bart/backup/'
$db_backup_dir = $backup_dir.'database/';
$uploads_backup_dir = $backup_dir.'uploads/';

# WebDav Settings
$webdav_url = 'http://...';

// http://sabre.io/dav/install/
