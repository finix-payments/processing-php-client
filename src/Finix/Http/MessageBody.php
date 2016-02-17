<?php

namespace Finix\Http;

abstract class MessageBody
{
    /**
     * @return    string    The Content-Type header.
     */
    public abstract function getContentType();

    /**
     * @return    string    The Content-Length header.
     */
    public abstract function getContentLength();

    /**
     * @return    string    The content.
     */
    public abstract function getContent();

    /**
     * @return    string    The content.
     */
    public function __toString() {
        return $this->getContent();
    }
}
