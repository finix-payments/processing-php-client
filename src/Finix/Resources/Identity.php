<?php
namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Resource;

class Identity extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('identities', 'id', '/'));
    }

    public function provisionMerchantOn(Merchant $merchant)
    {
        return $merchant->create($this->resource->getLink("merchants")->getHref());
    }

    public function createSettlement(Settlement $settlement)
    {
        return $settlement->create($this->resource->getLink("settlements")->getHref());
    }

    public function createPaymentCard(PaymentCard $card)
    {
        $card->state["identity"] = $this->id;
        return $card->create($this->resource->getLink("payment_instruments")->getHref());
    }

    public function createBankAccount(BankAccount $bankAccount)
    {
        $bankAccount->state["identity"] = $this->id;
        return $bankAccount->create($this->resource->getLink("payment_instruments")->getHref());
    }
}
