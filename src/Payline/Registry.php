<?php

namespace Payline;


class Registry
{
    protected $_resources = array();

    protected $_hrefSpecMap = array();

    /**
     * @param string $resource
     * @param \Payline\Hal\HrefSpec $hrefSpec
     */
    public function add($resource, $hrefSpec)
    {
        array_push($this->_resources, $resource);
        $this->_hrefSpecMap[$resource] = $hrefSpec;
    }

    public function match($uri)
    {
        foreach ($this->getResources() as $resource) {
            $spec = $this-$this->getHrefSpecForResource($resource);
            $result = $spec->match($uri);
            if ($result == null) {
                continue;
            }
            $result['class'] = $resource;

            return $result;
        }

        return null;
    }

    /**
     * @return \Payline\Resource[]
     */
    public function getResources()
    {
        return $this->_resources;
    }

    /**
     * @param string $resource
     * @return \Payline\Hal\HrefSpec
     */
    public function getHrefSpecForResource($resource)
    {
        return $this->_hrefSpecMap[$resource];
    }

}
