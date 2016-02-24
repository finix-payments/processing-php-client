<?php
namespace Payline\Resources;

use Payline\Hal\HrefSpec;
use Payline\Resource;

class Transfer extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('transfers', 'id', '/'));
    }

    /**
     * @param int $amount the amount to reverse
     * @return \Payline\Resources\Reversal
     * @throws \Payline\Hal\Exception\LinkNotUniqueException
     * @throws \Payline\Hal\Exception\RelNotFoundException
     */
    public function reverse($amount)
    {
        // TODO shouldn't this field be named: "reverse_amount" or "amount_to_reverse"
        $reversal = new Reversal(["refund_amount"=>$amount]);
        return $reversal->create($this->resource->getLink("reversals")->getHref());
    }
}