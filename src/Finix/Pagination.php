<?php

namespace Finix;

use Finix\Http\Request;
use Iterator;

class Pagination extends Resource implements Iterator
{
    private $resourceClass;

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
        $this->resourceClass = $class;
    }

    public function current()
    {
        return $this->state->items;
    }

    public function next()
    {
        $link = $this->resource->getLink('next')->getHref();
        $halResource = $this->client->sendRequest(new Request($link));
        $this->__construct($halResource, $this->resourceClass);
    }

    public function key()
    {
        return $this->page["offset"];
    }

    public function valid()
    {
        return $this->page["offset"] < $this->page["count"];
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
    }
}
