<?php

namespace Finix\Http;

class JsonBody extends MessageBody
{
    private $json;

    private $content;

    /**
     * @param $json    mixed    A string, an array or an object representing the JSON body.
     * @throws \Exception
     */
    public function __construct($json)
    {
        if (!is_array($json) && !is_object($json) && !is_string($json)) {
            throw new \Exception("JSON body must be a string, an array or an object representing the JSON body ('" . gettype($json) . "' provided).");
        }

        $this->json = $json;
    }

    /**
     * @return    mixed    A string, an array or an object representing the JSON body.
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @return    string    The Content-Type header.
     */
    public function getContentType()
    {
        return 'application/json';
    }

    /**
     * @return    string    The Content-Length header.
     */
    public function getContentLength()
    {
        return strlen($this->getContent());
    }

    /**
     * @return    string    The content.
     */
    public function getContent()
    {
        if ($this->content) {
            return $this->content;
        }

        if (is_array($this->json) || is_object($this->json)) {
            return $this->content = json_encode($this->json, JSON_UNESCAPED_UNICODE);
        } else {
            return $this->content = $this->json;
        }
    }
}
