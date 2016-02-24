<?php
namespace Payline\Resources;

use Payline\Hal\HrefSpec;
use Payline\Resource;

class Reversal extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(
            get_called_class(),
            new HrefSpec('reversals', 'id', '/'));
    }

    public function getParentTransferHref()
    {
        return $this->resource->getLink("parent")->getHref();
    }


}