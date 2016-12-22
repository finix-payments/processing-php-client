<?php
namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Resource;

abstract class PaymentInstrument extends Resource
{

    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('payment_instruments', 'id', '/'));
    }
}
