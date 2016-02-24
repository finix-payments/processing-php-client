<?php
namespace Payline\Hal\Exception;

use Payline\Hal\Resource;
use Payline\Http\BaseHttpException;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;

class HalException extends BaseHttpException
{

    /**
     * The response message body may be a string
     * representation of a Resource representing the error.
     *
     * This is basically a shortcut for Resource::fromJson(getResponseBody()).
     * @return  \Payline\Hal\Resource    The Resource returned by the response (may be empty).
     */
    public function getResponseResource() {
        return Resource::fromJson($this->getResponseBody());
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return string
     */
    protected function buildMessage($request, $response)
    {
        $resource = $this->getResponseBody();
        if(is_null($resource)) {
            $resource = '';
        }

        $message = sprintf(
            '[url] %s [http method] %s [status code] %s [reason phrase] %s: %s',
            $request->getUrl(),
            $request->getMethod(),
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            $resource
        );
        return $message;
    }

}