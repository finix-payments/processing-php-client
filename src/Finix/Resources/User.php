<?php

namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Resource;

class User extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('users', 'id', '/'));
    }

    public function createApplication(Application $application)
    {
        return $application->create($this->resource->getLink("applications")->getHref());
    }
}
