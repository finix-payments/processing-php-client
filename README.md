# Finix Processing HTTP Client

[![Circle CI](https://circleci.com/gh/finix-payments/processing-php-client.svg?style=svg&circle-token=e14235e0e783121b16391bca9cca82898e3ba34e)](https://circleci.com/gh/finix-payments/processing-php-client)

## Requirements

PHP 5.4 and later.

## Issues

Please use appropriately tagged github [issues](https://github.com/finix-payments/processing-php/issues) to request features or report bugs.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require finix/processing-php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Getting Started

If you do not wish to use Composer, you can download the [latest release](https://github.com/finix-payments/processing-php/releases). Then, `require` all bootstrap files:

```php
require('/path/to/Finix/Settings.php');
require('/path/to/Finix/Bootstrap.php');

use \Finix\Settings;
use \Finix\Bootstrap;

Settings::configure([
    "root_url" => "https://api-staging.finix.io",
    "username" => 'US4CoseCAaB5RjTYrnwfDrZa',
    "password" => '4ca5aef5-f077-4277-a785-00f2cab07c21'
]);

Bootstrap::init();
```

See the [tests](https://github.com/finix-payments/processing-php-client/tree/master/tests) for more details.

