<?php

require(__DIR__ . '/src/Payline/Settings.php');
Payline\Settings::configure(
    'https://api-test.payline.io',
    'USo7wNx4M4NAFZzPWQMLBHpM',
    '48919985-f2b2-4f42-a98f-b367dfc1316f');

require(__DIR__ . '/src/Payline/Bootstrap.php');
\Payline\Bootstrap::init();