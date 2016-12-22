<?php

namespace Finix\Resources;

use Finix\Hal\HrefSpec;


class PaymentCard extends PaymentInstrument
{

    public function __construct(array $state = [], array $links = null)
    {
        $state["type"] = "PAYMENT_CARD";
        parent::__construct($state, $links);
    }

    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('payment_instruments', 'id', '/'));
    }

    public function createUpdate(InstrumentUpdate $action)
    {
        return $action->create($this->resource->getLink("updates")->getHref());
    }

    public static function getUpdateUri($card_id, $update_id)
    {
        // TODO move this to Registry
        return "/" . self::getHrefSpec()->name . "/" . $card_id . "/" . InstrumentUpdate::getHrefSpec()->name . "?id=" . $update_id;
    }
}
