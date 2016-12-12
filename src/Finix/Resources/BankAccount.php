<?php

namespace Finix\Resources;

use Finix\Hal\HrefSpec;

class BankAccount extends PaymentInstrument
{
    public function __construct(array $state = [], array $links = null)
    {
        $state["type"] = "BANK_ACCOUNT";
        parent::__construct($state, $links);
    }

    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('payment_instruments', 'id', '/'));
    }
}
