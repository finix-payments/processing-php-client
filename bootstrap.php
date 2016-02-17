<?php

require(__DIR__ . '/src/Finix/Settings.php');
Finix\Settings::configure(
    'http://b.papi.staging.finix.io',
    'USwyuGJdVcsRTzDeX9smLVGQ',
    '968cb207-1abb-4100-9425-9a723e99eb10');

require(__DIR__ . '/src/Finix/Bootstrap.php');
\Finix\Bootstrap::init();