<?php
namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Resource;

class Verification extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('transfers', 'id', '/'));
    }
}