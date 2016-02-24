# Payline Processing HTTP Client

[![Circle CI](https://circleci.com/gh/payline-payments/processing-php-client.svg?style=svg&circle-token=e14235e0e783121b16391bca9cca82898e3ba34e)](https://circleci.com/gh/payline-payments/processing-php-client)

## Requirements

PHP 5.4 and later.

## Issues

Please use appropriately tagged github [issues](https://github.com/payline-payments/processing-php/issues) to request features or report bugs.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require payline/processing-php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Source Installation

If you do not wish to use Composer, you can download the [latest release](https://github.com/payline-payments/processing-php/releases). Then, `require` all bootstrap files:

```php
<?php
require_once("/path/to/Payline/Bootstrap.php");
\Payline\Bootstrap::init();
...
```

## Getting Started

```php
require(__DIR__ . '/src/Payline/Settings.php');
Payline\Settings::configure('https://api-test.payline.io', '$USERNAME', '$PASSWORD');
require(__DIR__ . '/src/Payline/Bootstrap.php');
\Payline\Bootstrap::init();
```

See the `tests/` for more details.

## Hacking

```bash
git clone https://github.com/payline-payments/processing-php-client.git
cd processing-php-client
composer install --prefer-source --no-interaction
```

### Running tests

`./vendor/bin/phpunit`

See `circle.yml` for more details.

### Debugging

- Install [MITM Proxy](https://mitmproxy.org/)
- `sudo mitmdump  -P https://api-test.payline.io -a -vv -p 80`
- Run the tests, see the request / response


