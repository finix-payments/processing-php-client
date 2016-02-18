<?php

namespace Finix\Http;

class MultipartBody extends MessageBody
{
    /** @var  \GuzzleHttp\Post\PostFile $postFile*/
    protected $postFile;

    /**
     * @param $multipart    mixed    A string, an array or an object representing the multipart form data body.
     * @throws \Exception
     */
    public function __construct($multipart)
    {
        if (!is_a($multipart, '\GuzzleHttp\Post\PostFile')) {
            throw new \Exception('Multipart body must be a GuzzleHttp\\Post\\PostFile representing the data body (\''
                . gettype($multipart) . "' provided).");
        }

        $this->postFile = $multipart;
    }

    /**
     * @return    string    The Content-Type header.
     */
    public function getContentType()
    {
        return 'multipart/form-data';
    }

    /**
     * @return  \GuzzleHttp\Post\PostFile    The content.
     */
    public function getContent()
    {
        return $this->postFile;
    }

    /**
     * @return    string    The Content-Length header.
     */
    public function getContentLength()
    {
        // no-op
    }
}
