<?php

namespace Finix\Http;

final class Request extends AbstractRequest {

    private $url;

    /**
     * @param $url          string      The URL
     * @param $method       string      GET (default), POST, PUT, PATCH or DELETE
     * @param $urlVariables array       The value of the URL variables contained in the URL template
     * @param $messageBody  MessageBody The messageBody to send with the request
     * @param $headers      array       Optional headers
     */
    public function __construct($url, $method = 'GET', array $urlVariables = array(), MessageBody $messageBody = null, array $headers = array()) {
        parent::__construct($method, $urlVariables, $messageBody, $headers);

        // Validate the URL
        $url = trim($url);
        if (!$url) {
            throw new \InvalidArgumentException('URL is empty.');
        }

        $this->url = $url;
    }

    /**
     * @return  string  The URL
     */
    public function getUrl()
    {
        return $this->url;
    }
}