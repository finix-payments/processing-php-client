<?php
namespace Payline\Resources;

use Payline\Hal\HrefSpec;
use Payline\Resource;

class Authorization extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('authorizations', 'id', '/'));
    }

}