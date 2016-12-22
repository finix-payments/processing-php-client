<?php
namespace Finix;

use Finix\Http\Auth\BasicAuthentication;
use Finix\Http\JsonBody;
use Finix\Http\Request;
use Finix\Resources\Verification;
use Finix\Utils\ArrayProxy;
use \stdClass;

abstract class Resource
{
    protected $resource;
    protected $state;
    protected $client;
    protected static $href;
    protected static $registry;

    /**
     * @param \Finix\Resource $resource
     * @return Hal\HrefSpec
     */
    public static function getHrefSpec($resource = null)
    {
        if (is_null($resource)) {
            $resource = get_called_class();
        }
        if (!is_string($resource)) {
            $resource = get_class($resource);
        }
        return self::getRegistry()->getHrefSpecForResource($resource);
    }

    /**
     * @return Hal\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return \Finix\Registry
     */
    public static function getRegistry()
    {
        return self::$registry;
    }

    public static function init()
    {
        self::$registry = new Registry();
    }

    public function __construct(array $state = null, array $links = null)
    {
        $this->client = Bootstrap::createClient();
        $this->setResource(new Hal\Resource($state, $links));
    }

    public function __get($name)
    {
        if ($this->state->has_key($name)) {
            return $this->state[$name];
        }

        // unknown
        $trace = debug_backtrace();
        trigger_error(
            sprintf('Undefined property via __get(): %s in %s on line %s',
                $name,
                $trace[0]['file'],
                $trace[0]['line']),
            E_USER_NOTICE
        );

        return null;
    }

    public function __set($name, $value)
    {
        $this->state[$name] = $value;
    }

    public function __isset($name)
    {
        if (array_key_exists($name, $this->resource->getAllLinks()) ||
            array_key_exists($name, $this->resource->getState())
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $id the identifier of the resource
     * @return \Finix\Resource
     * @throws Hal\Exception\HalClientErrorException
     * @throws Hal\Exception\HalException
     * @throws Hal\Exception\HalRedirectionException
     * @throws Hal\Exception\HalServerErrorException
     */
    public static function retrieve($id_or_url)
    {
        $uri = filter_var($id_or_url, FILTER_VALIDATE_URL) ?
            $id_or_url : self::getHrefSpec()->collection_uri . '/' . $id_or_url;
        $resource = Bootstrap::createClient()->sendRequest(new Request($uri));
        $class = get_called_class();
        $state = $resource->getState();
        if (sizeof($resource->getAllEmbeddedResources()) > 0) {
            $items = $resource->getAllEmbeddedResources();
            $items = reset($items);
            if (sizeof($items) == 1) {
                $state = $items[0]->getState();
            }
        }
        return new $class($state, $resource->getAllLinks());
    }

    public static function getPagination($resource)
    {
        $cls =  get_called_class();
        $halResource = Bootstrap::createClient()->sendRequest(new Request($resource->getHref($cls::getHrefSpec()->name)));
        return new Pagination($halResource, $cls);
    }

    public function refresh()
    {
        $request = new Request(
            $this->resource->getLink("self")->getHref(),
            'GET'
        );
        $resource = $this->getClient()->sendRequest($request);
        $this->setResource($resource);
        return $this;
    }

    /**
     * @return \Finix\Resource
     * @throws Hal\Exception\HalClientErrorException
     * @throws Hal\Exception\HalException
     * @throws Hal\Exception\HalRedirectionException
     * @throws Hal\Exception\HalServerErrorException
     * @throws Hal\Exception\LinkNotUniqueException
     * @throws Hal\Exception\RelNotFoundException
     */
    public function save()
    {
        if (empty($this->state["tags"])) {
            $this->state["tags"] = new stdClass();
        }

        $payload = new JsonBody(iterator_to_array($this->state));
        if ($this->isUpdate()) {
            $request = new Request(
                $this->resource->getLink("self")->getHref(),
                'PUT',
                array(),
                $payload
            );
            $resource = $this->getClient()->sendRequest($request);
            $this->setResource($resource);
        }
        else { // it is create
            $request = new Request(
                $this->getHrefSpec($this)->collection_uri,
                'POST',
                array(),
                $payload
            );
            $resource = $this->getClient()->sendRequest($request);
            $this->setResource($resource);
        }
        return $this;
    }

    /**
     * @param string $href
     * @return \Finix\Resource
     * @throws Hal\Exception\HalClientErrorException
     * @throws Hal\Exception\HalException
     * @throws Hal\Exception\HalRedirectionException
     * @throws Hal\Exception\HalServerErrorException
     */
    protected function create($href)
    {
        $payload = new JsonBody($this->state->count() == 0 ? "{}" : iterator_to_array($this->state));
        $request = new Request($href, 'POST', array(), $payload);
        $resource = $this->getClient()->sendRequest($request);
        $this->setResource($resource);
        return $this;
    }

    /**
     * @return bool
     */
    private function isUpdate()
    {
        return isset($this->resource->getState()['id']);
    }


    /**
     * @param string $rel
     * @return string
     */
    public function getHref($rel = "self")
    {
        return $this->resource->getLink($rel)->getHref();
    }

    /**
     * @param Hal\Resource $resource
     */
    private function setResource($resource)
    {
        $this->resource = $resource;
        $this->state = new ArrayProxy($resource->getState());
    }

    public function verifyOn(Verification $verification)
    {
        $verifyLink = $this->resource->getLink("verifications")->getHref();
        return $verification->create($verifyLink);
    }

//    public function update(array $array=[])
//    {
//        $updateLink = $this->resource->getLink("updates")->getHref();
//        $payload = new JsonBody($this->state->count() == 0 ? "{}" : iterator_to_array($this->state));
//        $request = new Request($href, 'POST', array(), $payload);
//        $resource = $this->getClient()->sendRequest($request);
//        $this->setResource($resource);
//        return $this;
////        return $application->create($this->resource->getLink("applications")->getHref());
////        return $verification->create($updateLink);
//    }
}
