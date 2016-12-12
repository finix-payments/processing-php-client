<?php

namespace Finix\Resources;

use Finix\Hal\HrefSpec;


class PaymentCard extends PaymentInstrument
{

    public function __construct(array $state = [], array $links = null)
    {
        $state["type"] = "PAYMENT_CARD";
        parent::__construct($state, $links);
    }

    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('payment_instruments', 'id', '/'));
    }
}
