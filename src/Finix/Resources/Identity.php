<?php
namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Resource;

class Identity extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(
            get_called_class(),
            new HrefSpec('identities', 'id', '/'));
    }

    /**
     * @param string $processor the processor to underwrite the merchant on
     * @return \Finix\Resources\Merchant
     * @throws \Finix\Hal\Exception\LinkNotUniqueException
     * @throws \Finix\Hal\Exception\RelNotFoundException
     */
    public function provisionMerchantOn($processor)
    {
        $merchant = new Merchant(["processor"=>$processor]);
        return $merchant->create($this->resource->getLink("underwriting")->getHref());
    }


    /**
     * @param string $processor the processor to settle the funds out to
     * @return \Finix\Resources\Settlement
     * @throws \Finix\Hal\Exception\LinkNotUniqueException
     * @throws \Finix\Hal\Exception\RelNotFoundException
     */
    public function createSettlement($processor)
    {
        $settlement = new Settlement(["processor"=>$processor]);
        return $settlement->create($this->resource->getLink("settlements")->getHref());
    }
}