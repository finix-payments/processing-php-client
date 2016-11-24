<?php
namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Resource;

class Application extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('applications', 'id', '/'));
    }

    public function createPartnerUser(User $user)
    {
        return $user->create($this->resource->getLink("users")->getHref());
    }

    public function createProcessor(Processor $processor)
    {
        return $processor->create($this->resource->getLink("processors")->getHref());
    }
}
