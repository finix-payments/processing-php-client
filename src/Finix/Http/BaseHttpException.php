<?php
namespace Finix\Http;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;

class BaseHttpException extends \Exception {
    private $request;
    private $response;

    public function __construct(RequestInterface $request, ResponseInterface $response) {
        $this->request = $request;
        $this->response = $response;

        $message = $this->buildMessage($request, $response);
        parent::__construct($message);

    }

    /**
     * @return RequestInterface  The HTTP request causing the Exception.
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return ResponseInterface The HTTP response causing the Exception.
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * The magic setter is overridden to insure immutability.
     * @param $name
     * @param $value
     */
    public function __set($name, $value) { }

    /**
     * This is basically a shortcut for for getResponse()->getStatusCode()
     * @return  string  The HTTP status code.
     */
    public function getStatusCode() {
        return $this->response->getStatusCode();
    }

    /**
     * This is basically a shortcut for getResponse()->getReasonPhrase()
     * @return  string  The HTTP reason phrase.
     */
    public function getReasonPhrase() {
        return $this->response->getReasonPhrase();
    }

    /**
     * This is basically a shortcut for (string) getResponse()->getBody()
     * @return  string  The response body.
     */
    public function getResponseBody() {
        return (string) $this->response->getBody();
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return string
     */
    protected function buildMessage($request, $response)
    {
        $message = sprintf(
             '[url] %s [http method] %s [status code] %s [reason phrase] %s.',
             $request->getUrl(),
             $request->getMethod(),
             $response->getStatusCode(),
             $response->getReasonPhrase()
         );
        return $message;
    }

}
