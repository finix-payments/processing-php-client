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

    public function capture($amount, $fee = 0)
    {
        $this->state["capture_amount"] = $amount;
        $this->state["fee"] = $fee;
        return $this->save();
    }

    public function void($voidMe)
    {
        $this->state["void_me"] = $voidMe;
        return $this->save();
    }
}
