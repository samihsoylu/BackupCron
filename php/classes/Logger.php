<?php
class Logger {

  /*
  * Saves an error message to file. Creates a .log file if it does not exist
  *
  * @param string $errorFile - Name of the file
  * @param string $errorMessage - Message to put in to file.
  * @param array $init - contains values of settings
  * @return void
  */
  public static function SaveError($errorName, $errorMessage, $init) {

    # Where the error will be saved
    $errorFile = BASE_PATH.'logs/'.$errorName.'.log';

    # Before the error message, date is displayed
    $date_of_error = "[".date('d M Y H:i:s')."] :: ";

    # Creates log if it does not exist
    if(!file_exists($errorFile)) {
      $openedFile = fopen($errorFile, "w");
      fclose($openedFile);
    }

    # Reads error log
    $errorFileData = file_get_contents($errorFile);

    # Amendments to variable
    $errorFileData .= $date_of_error . $errorMessage . "\n\n";

    # Writes to file
    file_put_contents($errorFile, $errorFileData);

    # Send an email to an admin
    @mail($init['debug']['email'], $init['debug']['apptitle'].': Error logger', $errorMessage);

  }

}
