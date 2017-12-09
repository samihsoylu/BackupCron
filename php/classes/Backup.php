<?php

class Backup {

  # @string backup directory path
  private $backup_directory, $db_backup_directory, $uploads_backup_directory;

  # @string database details
  private $db_host, $db_username, $db_password, $db_schema;

  # @string uploads directory location, the directory that will be zipped
  private $uploads_directory;

  # @string backups path after backup is complete
  private $uploads_backup_path, $db_backup_path;

  # @string names of the files that will be used to save during the backup process.
  private $db_filename, $uploads_filename;

  /*
  * Constructor checks whether exec is enabled, and then defines variables
  *
  * @param array $init, initialises class. Expected inputs:
  *   [db][host]         - Database Host
  *   [db][username]     - Database Username
  *   [db][password]     - Database Password
  *   [db][schema]       - Database Schema
  *   [app][backups_dir] - Location where backups should be made
  *   [app][uploads_dir] - Location of uploads directory, it will be backed up.
  *
  */
  public function __construct($init) {

    # Check whether exec is enabled
    if(!$this->exec_enabled()) {
        throw new Exception("You must have exec enabled to use the backup class.");
    }

    $this->defineVariables($init);

  }

  /*
  * Setter, sets all variables that are used within the class
  *
  * @return void
  */
  private function defineVariables($init) {

    # Database credentials
    $this->db_host                  = $init['db']['host'];
    $this->db_username              = $init['db']['username'];
    $this->db_password              = $init['db']['password'];
    $this->db_schema                = $init['db']['schema'];

    # Set backup directories
    $this->backup_directory         = $init['app']['backups_dir'];
    $this->db_backup_directory      = $init['app']['backups_dir'].'/db/';
    $this->uploads_backup_directory = $init['app']['backups_dir'].'/uploads/';

    # Directory that will be zipped;
    $this->uploads_directory        = $init['app']['uploads_dir'];

    # File name combined with date (Should look like: 01-01-2017_db.sql)
    $this->db_filename = date('d-m-Y').'_db.sql';

    # File name combined with date (Should look like: 01-01-2017_uploads.zip)
    $this->uploads_filename = date('d-m-Y').'_uploads.zip';

    # path with the file name Example to what it looks like: /path/to/backup/dir/01-01-2017_db.sql
    $this->db_backup_path = $this->db_backup_directory.$this->db_filename;

    # Full save path with the file name /path/to/backup/dir/01-01-2017_db.sql
    $this->uploads_backup_path = $this->uploads_backup_directory.$this->uploads_filename;

  }

  /*
  * Function checks whether exec is enabled on the server
  * specifically, looks in to the ini file.
  *
  * @return bool
  */
  private function exec_enabled() {
    $disabled = explode(',', ini_get('disable_functions'));
    return !in_array('exec', $disabled); // Returns true if enabled
  }

  /*
  * Creates a MySQL Dump using exec. Will throw an exception if
  * there is an issues occur while using exec()
  *
  * @return void
  */
  public function CreateSQLDump() {

      # Requirements for the exec function
      # Used later to check whether the function executed correctly
      $return_var = NULL;
      $output = NULL;

      # Linux command to backup the database
      $command = "/usr/bin/mysqldump --single-transaction -u ".$this->db_username." -h ".$this->db_host." -p".$this->db_password." ".$this->db_schema." > ".$this->db_backup_path;

      # Execute the command on the server
      exec($command, $output, $return_var);

      # If exec did not work, throws an error with output.
      if($return_var) {
        throw new Exception($output);
      }
  }

  /*
  * Zips uploads directory. Will throw an exception if
  * there is an issues occur while using exec()
  *
  * @return void
  */
  public function CreateUploadsZIP() {

      # Requirements for the exec function
      # Used later to check whether the function executed correctly
      $return_var = NULL;
      $output = NULL;

      # Command: zip -r destination.zip /source/uploads
      # Command: zip -r /home/bart/backups/uploads/09-12-2017_uploads.zip /home/bart/public/web/app/uploads
      $command = 'zip -r '.$this->uploads_backup_path.' '. $this->uploads_directory;

      # Execute the command on the server
      exec($command, $output, $return_var);

      # If exec did not work, throws an error with output.
      if($return_var) {
        throw new Exception($output);
      }
  }

  /*
  * Getter, returns SQL dump path
  *
  * @return string
  */
  public function get_path_of_dumped_sql() {
    return $this->db_backup_path;
  }

  /*
  * Getter, returns zipped uploads directory path
  *
  * @return string
  */
  public function get_path_of_zipped_uploads() {
    return $this->uploads_backup_path;
  }

  /*
  * Getter, returns used file name to save sql
  *
  * @return string
  */
  public function get_filename_of_dumped_sql() {
    return $this->db_filename;
  }

  /*
  * Getter, returns used file name to save uploads
  *
  * @return string
  */
  public function get_filename_of_zipped_uploads() {
    return $this->uploads_filename;
  }

}
