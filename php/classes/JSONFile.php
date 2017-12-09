<?php
class JSONFile {

  /*
  * Reads JSON file and returns the data
  *
  * @param $file - File location to open & read
  * @param $openMode - File opening mode
  */
  public static function read($file, $openMode='r') {

    if(!file_exists($file)) {
      throw new Exception("File '".$file."' in parameter does not exist, please make sure the file exists.");
    }

    # Opens
    $f = fopen($file, $openMode);

    # Read total file. If file is empty, read 1 to not have a warning.
    $JSONData = fread($f, max(filesize($file), 1));

    # Decodes data
    $arrayData = JSON_decode($JSONData, true);

    # Returns it
    return $arrayData;

  }

  /*
  * Writes in to JSON file and closes file
  *
  * @string $file - File location to open & write
  *
  * @array $data - The data that will be write n in to the file.
  * @string $openMode - The mode this file will be opened.
  */
  public static function write($file, $data, $openMode='w+') {

    # Opens file
    $f = fopen($file, $openMode);

    # Encodes and puts in data
    fputs($f, JSON_encode($data, JSON_PRETTY_PRINT));

    # Closes file
    fclose($f);

  } // End of write()

}
