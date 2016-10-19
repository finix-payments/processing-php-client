<?php

namespace Finix\Resources;


class PaymentCard extends PaymentInstrument
{

    public function __construct(array $state = [], array $links = null)
    {
        $state["type"] = "PAYMENT_CARD";
        parent::__construct($state, $links);
    }
}
