<?php
namespace Payline\Resources;

use Payline\Hal\HrefSpec;
use Payline\Resource;

class Merchant extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(
            get_called_class(),
            new HrefSpec('merchants', 'id', '/'));
    }

}