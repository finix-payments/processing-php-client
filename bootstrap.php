<?php

require(__DIR__ . '/src/Payline/Settings.php');
Payline\Settings::configure(
    'https://api-test.payline.io',
    'US6DsJ1V7qFZhFx27wuntKf1',
    '5a363493-700d-4a03-8dce-dea2484a52df');

require(__DIR__ . '/src/Payline/Bootstrap.php');
\Payline\Bootstrap::init();