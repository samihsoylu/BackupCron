<?php

# Server settings
date_default_timezone_set('Europe/Amsterdam');
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);

# Imports all classes
spl_autoload_register(function($class) {
  require_once dirname(__FILE__).'/classes/' . $class . '.php';
});

# Read from config file
$init = JSONFile::read(dirname(__FILE__).'../.env');
