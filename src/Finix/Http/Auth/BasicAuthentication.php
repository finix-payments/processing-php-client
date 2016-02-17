<?php

namespace Finix\Http\Auth;

use Finix\Http;
use Finix\Http\AbstractClient;
use GuzzleHttp\Message\RequestInterface;

/**
 * Using a <a href="https://tools.ietf.org/html/rfc2617#section-2">Basic authentication</a>
 * to authenticate to the server.
 */
final class BasicAuthentication implements AuthenticationMethodInterface
{
    private $user_id;
    private $password;

    /**
     * The basic authentication method using the
     * Basic authorization header composed of a
     * userid and a password.
     *
     * @param $user_id
     * @param $password
     */
    public function __construct($user_id, $password)
    {
        $this->user_id           = $user_id;
        $this->password         = $password;
    }


    /**
     * @return    string    The first part of the basic authentication.
     */
    public function getUserid()
    {
        return $this->user_id;
    }

    /**
     * @return    string    The second part of the basic authentication.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Adds the authorization header to the request.
     * If we do not have an authorization header yet, we annotate the request with one.
     * @param Http\AbstractClient $_client | The $_client client used to send the request.
     * @param RequestInterface $httpRequest | The HTTP request before it is sent.
     * @return Http\BaseHttpException|void
     * @throws \Exception
     */
    public function authorizeRequest(AbstractClient $_client, RequestInterface &$httpRequest)
    {
        if ($this->isRequestAuthorized($httpRequest)){
            return;
        }

        // We have a valid token (make sure to clean the Authorization header)
        $httpRequest->removeHeader('Authorization');
        $httpRequest->addHeader('Authorization', 'Basic ' . $this->getCredentials());
    }

    /**
     * @param RequestInterface $httpRequest The HTTP request before it is sent.
     * @return bool false if the request needs to be authorized
     */
    private function isRequestAuthorized(RequestInterface $httpRequest)
    {
        $authorization = trim($httpRequest->getHeader('Authorization'));
        if (!$authorization) {
            return false;
        } else {
            return (strpos($authorization, 'Basic') === 0);
        }
    }

    /**
     * Creates a <a href="https://tools.ietf.org/html/rfc2617#section-2">Basic authentication</a>
     * credential pair.
     * @throws \Exception
     */
    private function getCredentials()
    {
        $basic = base64_encode($this->user_id . ':' . $this->password);
        return $basic;
    }
}
