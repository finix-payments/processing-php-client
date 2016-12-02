<?php

namespace Finix;


class Pagination extends Resource
{

    public function __construct(Hal\Resource $halResource, $class)
    {
        parent::__construct($halResource->getState(), $halResource->getAllLinks());
        $resources = $halResource->getAllEmbeddedResources();
        $resources = reset($resources);
        $items = array();
        foreach ($resources as $name => $resource) {
            array_push($items, new $class($resource->getState(), $resource->getAllLinks()));
        }
        $this->state->items = $items;
    }
}
