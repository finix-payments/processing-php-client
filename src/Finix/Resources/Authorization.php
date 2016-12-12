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

    public function capture(array $cap = [])
    {
        foreach ($cap as $key => $value) {
            $this->state[$key] = $value;
        }
        return $this->save();
    }

    public function void($voidMe = true)
    {
        $this->state["void_me"] = $voidMe;
        return $this->save();
    }
}
