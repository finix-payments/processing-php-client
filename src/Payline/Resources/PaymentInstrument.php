<?php
namespace Payline\Resources;

use Payline\Hal\HrefSpec;
use Payline\Resource;

class PaymentInstrument extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(),
            new HrefSpec('payment_instruments', 'id', '/'));
    }
}