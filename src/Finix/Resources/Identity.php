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
     * @param string $currency the currency for the settlment
     * @return \Finix\Resources\Settlement
     * @throws \Finix\Hal\Exception\LinkNotUniqueException
     * @throws \Finix\Hal\Exception\RelNotFoundException
     */
    public function createSettlement(array $args)
    {
        // TODO passing identity field by default bc it's redudant, users shouldn't need to pass this. remove once bug is fixed
        $settlement = new Settlement(["processor"=>$args['processor'], "currency"=>$args['currency'], "identity"=>$this->resource->getState()["id"]]);
        // TODO shouldn't this link should not be construcuted, update when identity resource is fixed
        return $settlement->create($this->resource->getLink("self")->getHref() . "/settlements");
    }

    /**
     * @param string $processor the processor to settle the funds out to
     * @param string $currency the currency for the settlment
     * @return \Finix\Resources\Settlement
     * @throws \Finix\Hal\Exception\LinkNotUniqueException
     * @throws \Finix\Hal\Exception\RelNotFoundException
     */
    public function verifyOn(array $args)
    {
        $verification = new Verification(["processor"=>$args['processor']]);
        // TODO shouldn't this link should not be construcuted, update when identity resource is fixed
        return $verification->create($this->resource->getLink("verifications")->getHref());
    }
}