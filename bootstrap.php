<?php

require(__DIR__ . '/src/Finix/Settings.php');
require(__DIR__ . '/tests/Finix/Fixtures.php');
require(__DIR__ . '/src/Finix/Bootstrap.php');

use \Finix\Tests\Fixtures;
use \Finix\Settings;
use \Finix\Bootstrap;

Settings::configure([
    "root_url" => Fixtures::$apiUrl
]);

Bootstrap::init();
