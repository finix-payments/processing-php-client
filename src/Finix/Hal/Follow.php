<?php

namespace Finix\Hal;

use Finix\Http\AbstractRequest;
use Finix\Http\MessageBody;
use Finix\Hal\Exception\LinkNotUniqueException;
use Finix\Hal\Exception\RelNotFoundException;

final class Follow extends AbstractRequest
{
    private $rel;

    /**
     * @param $rel            RegisteredRel|CustomRel            The relation type.
     * @param $method        string        GET, POST, PUT, PATCH or DELETE
     * @param $urlVariables    array        The value of the URL variables contained in the URL template
     * @param $messageBody    MessageBody    The messageBody to send with the request
     * @param $headers        array        Optional headers
     */
    public function __construct($rel, $method = 'GET', array $urlVariables = array(), MessageBody $messageBody = null, array $headers = array())
    {
        parent::__construct($method, $urlVariables, $messageBody, $headers);
        $this->rel = $rel;
    }

    /**
     * Looks for a unique Link referenced by the set
     * relation type (RegisteredRel|CustomRel) and returns its href property.
     * @param $resource \Finix\Hal\Resource             The Resource containing a Link referenced
     *                                     by the set relation type (RegisteredRel|CustomRel).
     * @return string    The URL in the href property of the Link.
     * @throws LinkNotUniqueException
     * @throws RelNotFoundException
     */
    public function getUrl($resource) {
        return $resource->getLink($this->getRel())->getHref();
    }

    /**
     * @return    RegisteredRel|CustomRel        The relation type.
     */
    public function getRel() {
        return $this->rel;
    }
}