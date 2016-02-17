<?php

namespace Finix\Http;

final class Response
{

    protected $method;
    protected $urlVariables;
    protected $responseBody;
    protected $headers;

    /**
    * @param $method         string       GET, POST, PUT, PATCH or DELETE
    * @param $urlVariables   array        The value of the URL variables contained in the URL template
    * @param $responseBody   array        The response body array to send with the request
    * @param $headers        array        Optional headers
    */
    public function __construct($method = 'GET', array $urlVariables = array(), array $responseBody = null, array $headers = array())
    {
        $method = strtoupper(trim($method));
        if (!in_array($method, array('GET', 'POST', 'PUT', 'PATCH', 'DELETE'))){
            throw new \InvalidArgumentException(sprintf('Method must be one of GET, POST, PUT, PATCH or DELETE, currently %s.', $method));
        }

         $this->method       = $method;
         $this->urlVariables = $urlVariables;
         $this->responseBody  = $responseBody;
         $this->headers      = $headers;
    }

    /**
    * @return    int     GET, POST, PUT, PATCH or DELETE
    */
    public function getMethod()
    {
        return $this->method;
    }

    /**
    * @return    array    The value of the URL variables contained in the URL template.
    */
    public function getUrlVariables()
    {
        return $this->urlVariables;
    }

    /**
    * @return    array    The response body to be sent with the request.
    */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
    * @return   array The optional headers.
    */
    public function getHeaders()
    {
        return $this->headers;
    }
}