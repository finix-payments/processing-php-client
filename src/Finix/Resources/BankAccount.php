<?php

namespace Finix\Resources;


class BankAccount extends PaymentInstrument
{
    public function __construct(array $state = [], array $links = null)
    {
        $state["type"] = "BANK_ACCOUNT";
        parent::__construct($state, $links);
    }
}
