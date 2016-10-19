<?php
namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Resource;

class Authorization extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('authorizations', 'id', '/'));
    }

    public function capture($amount) {
        $this->state["capture_amount"] = $amount;
        return $this->save();
    }

    public function setVoidMe($voidMe) {
        $this->state["void_me"] = $voidMe;
        return $this->save();
    }
}
