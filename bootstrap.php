<?php

require(__DIR__ . '/src/Finix/Settings.php');
require(__DIR__ . '/tests/Finix/Fixtures.php');
require(__DIR__ . '/src/Finix/Bootstrap.php');

use \Finix\Tests\Fixtures;
use \Finix\Settings;
use \Finix\Bootstrap;

$processing_url = getenv("PROCESSING_URL");
if ($processing_url == null) {
    $processing_url =  "https://api-staging.finix.io/";
}

Fixtures::$apiUrl = $processing_url;

Settings::configure([
    "root_url" => Fixtures::$apiUrl
]);

Bootstrap::init();
