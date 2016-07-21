<?php

require(__DIR__ . '/src/Finix/Settings.php');
Finix\Settings::configure(
    'https://api-staging.finix.io/',
    'USkmuYs3Sb2kcryiicgHbxGE',
    '54fde51b-6031-4118-acf0-72f4374e8972'
);

require(__DIR__ . '/src/Finix/Bootstrap.php');
\Finix\Bootstrap::init();