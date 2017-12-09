<?php

use Sabre\DAV\Client;

# Dependencies (Composer auto loader)
require dirname(__FILE__).'/vendor/autoload.php';

# Imports all classes.
require dirname(__FILE__).'/php/init.php';

/* * * * * * * * * * * * * * * * * * * * * * * * * *
* CREATE A BACKUP OF DATABASE AND UPLOADS DIRECTORY
* * * * * * * * * * * * * * * * * * * * * * * * * */
try {

  # Instantiate backup class
  $backup = new Backup($init);

  # Backup the database
  $backup->CreateSQLDump();

  # Back up the uploads directory
  $backup->CreateUploadsZIP();

  # Get backed up zip paths (path/to/filename.zip);
  $dumped_sql_path     = $backup->get_path_of_dumped_sql();
  $zipped_uploads_path = $backup->get_path_of_zipped_uploads();

  # Get file names of backed up files (filename.zip)
  $dumped_sql_filename     = $backup->get_filename_of_dumped_sql();
  $zipped_uploads_filename = $backup->get_filename_of_zipped_uploads();

  unset($backup);

} catch (Exception $e) {
  Logger::SaveError(basename(__FILE__, '.php'), $e, $init);
  exit();
}

/* * * * * * * * * * * * * * * * * * * * * * * * * *
* WEB DAV CLIENT, UPLOADING DATA TO SERVER
* * * * * * * * * * * * * * * * * * * * * * * * * */
try {

  // Documentation URL http://sabre.io/dav/davclient/

  # Settings for dav client
  $settings = array(
      'baseUri'  => $init['webdav']['remote'],
      'userName' => $init['webdav']['username'],
      'password' => $init['webdav']['password']
  );

  # Instantiate object
  $client = new Client($settings);

  # Upload to WebDav server
  $db_upload_response  = $client->request('PUT', $init['webdav']['sql_backup_dir'].$dumped_sql_filename, fopen($dumped_sql_path, 'r'));
  $zip_upload_response = $client->request('PUT', $init['webdav']['uploads_backup_dir'].$zipped_uploads_filename, fopen($zipped_uploads_path, 'r'));

  # Delete the files after sync;
  unlink($dumped_sql_path);
  unlink($zipped_uploads_path);

  unset($client);

} catch (Exception $e) {
  Logger::SaveError(basename(__FILE__, '.php'), $e, $init);
  exit();
}
