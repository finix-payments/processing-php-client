<?php

require(__DIR__ . '/src/Finix/Settings.php');
Finix\Settings::configure(
    'http://b.papi.staging.finix.io',
    'US7AQLoX6FtZcPDttFAafEz2',
    'f3276399-20f4-4bc3-aff0-71131cb347b8');

require(__DIR__ . '/src/Finix/Bootstrap.php');
\Finix\Bootstrap::init();