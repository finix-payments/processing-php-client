<?php

namespace Payline\Http\Auth;

use Payline\Http;
use Payline\Http\AbstractClient;
use \GuzzleHttp\Message\RequestInterface;

/**
 * The <a href="https://tools.ietf.org/html/rfc6749">Oauth2 authentication</a> using a
 * <a href="https://tools.ietf.org/html/rfc2617#section-2">Basic authentication</a>
 * to get the access token.
 */
final class Oauth2BasicAuthentication implements AuthenticationMethodInterface
{
    private $tokenEndPointUrl;
    private $userid;
    private $password;
    private $grantType;
    private $scope;

    private $token;

    /**
     * The Oauth2 authentication method using the
     * Basic authorization header composed of a
     * userid and a password.
     *
     * The default grant_type parameter is "client_credentials"
     * and the default scope is "api".
     * @param $tokenEndPointUrl
     * @param $userid
     * @param $password
     * @param string $scope
     * @param string $grantType
     * @param ExpirableToken $token
     */
    public function __construct($tokenEndPointUrl, $userid, $password, $scope = 'api',
                    $grantType = 'client_credentials', ExpirableToken $token = null)
    {
        $this->tokenEndPointUrl = $tokenEndPointUrl;
        $this->userid           = $userid;
        $this->password         = $password;
        $this->grantType        = $grantType;
        $this->scope            = $scope;
    }

    /**
     * @return    string    The API server authentication end point.
     */
    public function getTokenEndPointUrl()
    {
        return $this->tokenEndPointUrl;
    }

    /**
     * @return    string    The first part of the oauth2 authentication.
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @return    string    The second part of the oauth2 authentication.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return    string    The grant_type parameter.
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * @return    string    The scope parameter.
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return    ExpirableToken    The last token used.
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Adds the authorization header to the request with a valid token.
     * If we do not have a valid token yet, we send a request for one.
     * @param Http\AbstractClient $client | The $client client used to send the request.
     * @param RequestInterface $httpRequest | The HTTP request before it is sent.
     * @return Http\BaseHttpException|void
     * @throws \Exception
     */
    public function authorizeRequest(AbstractClient $client, RequestInterface &$httpRequest)
    {
        if ($this->isRequestAuthorized($httpRequest)){
            return;
        }

        // Request a new access token if needed
        if (!$this->isTokenStillValid()) {
            $this->getAccessToken($client);
        }

        // We have a valid token (make sure to clean the Authorization header)
        $httpRequest->removeHeader('Authorization');
        $httpRequest->setHeader('Authorization', 'Bearer ' . $this->getToken()->getValue());
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
            return (strpos($authorization, 'Basic') === 0 || strpos($authorization, 'Bearer') === 0);
        }
    }

    /**
     * Sends a request for an access token.
     * @param AbstractClient $client | The client used to send the request.
     * @throws \Exception
     */
    private function getAccessToken(AbstractClient $client)
    {
        $urlEncodedBody = new Http\UrlEncodedBody(array(
            'grant_type'    => $this->grantType,
            'scope'            => $this->scope
        ));

        $basic = base64_encode($this->userid . ':' . $this->password);
        $authorizationHeader = [
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $basic
        ];

        $request = new Http\Request(
            $this->tokenEndPointUrl,
            'POST',
            array(),
            $urlEncodedBody,
            $authorizationHeader
        );

        // Send the request
        $response = $client->sendRequest($request);
        $state = $response->getResponseBody()['state'];

        // Check the response
        if (!isset($state['access_token']) || !isset($state['expires_in'])) {
            throw new \Exception('The authentication was a success but the response did not contain the token or its validity limit.');
        }

        // We update the token
        $this->token = new ExpirableToken($state['access_token'], ($this->getTime() + $state['expires_in']));
    }

    /**
     * Checks if the token is till valid at the time
     * this method is called.
     * @return boolean
     */
    private function isTokenStillValid()
    {
        return ($this->token != null && $this->getToken()->isValidUntil($this->getTime()));
    }

    /**
     * It is important to use the same method when setting
     * the expiration time and checking if it is still valid.
     * @return int The current time in seconds.
     */
    private function getTime()
    {
        return time();
    }

}
