<?php
namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Resource;

class Transfer extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('transfers', 'id', '/'));
    }

    /**
     * @param int $amount the amount to reverse
     * @return \Finix\Resources\Reversal
     * @throws \Finix\Hal\Exception\LinkNotUniqueException
     * @throws \Finix\Hal\Exception\RelNotFoundException
     */
    public function reverse($amount)
    {
        // TODO shouldn't this field be named: "reverse_amount" or "amount_to_reserve"
        $reversal = new Reversal(["refund_amount"=>$amount]);
        return $reversal->create($this->resource->getLink("reversals")->getHref());
    }
}