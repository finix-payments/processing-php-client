<?php
namespace Payline\Resources;

use Payline\Hal\HrefSpec;
use Payline\Resource;

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
     * @return \Payline\Resources\Merchant
     * @throws \Payline\Hal\Exception\LinkNotUniqueException
     * @throws \Payline\Hal\Exception\RelNotFoundException
     */
    public function provisionMerchantOn($processor)
    {
        $merchant = new Merchant(["processor"=>$processor]);
        return $merchant->create($this->resource->getLink("underwriting")->getHref());
    }

}