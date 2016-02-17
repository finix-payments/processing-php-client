<?php

require(__DIR__ . '/src/Finix/Settings.php');
Finix\Settings::configure(
    'http://b.papi.staging.finix.io',
    'USo7wNx4M4NAFZzPWQMLBHpM',
    '48919985-f2b2-4f42-a98f-b367dfc1316f');

require(__DIR__ . '/src/Finix/Bootstrap.php');
\Finix\Bootstrap::init();