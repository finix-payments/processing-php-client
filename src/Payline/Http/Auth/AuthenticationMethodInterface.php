<?php
namespace Payline\Http\Auth;

use Payline\Http\AbstractClient;
use Payline\Http\BaseHttpException;
use GuzzleHttp\Message\RequestInterface;

interface AuthenticationMethodInterface {

    /**
     * This is called right before sending the HTTP request.
     * @param AbstractClient| $client The client used to send the request.
     * @param RequestInterface| $httpRequest The HTTP request before it is sent.
     * @return BaseHttpException
     */
    public function authorizeRequest(AbstractClient $client, RequestInterface &$httpRequest);
}
