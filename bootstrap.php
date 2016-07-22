<?php

require(__DIR__ . '/src/Finix/Settings.php');
require(__DIR__ . '/tests/Finix/SampleData.php');
require(__DIR__ . '/src/Finix/Bootstrap.php');

use \Finix\Tests\SampleData;
use \Finix\Settings;
use \Finix\Bootstrap;

Settings::configure(
  SampleData::$apiUrl,
  SampleData::$username,
  SampleData::$password
);

Bootstrap::init();