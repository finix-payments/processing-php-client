<?php
namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Http\Request;
use Finix\Resource;

class Dispute extends Resource
{
    public static function init()
    {
        self::getRegistry()->add(get_called_class(), new HrefSpec('disputes', 'id', '/'));
    }

    public function uploadFile($filePath)
    {
        $realFilePath = realpath($filePath);
        if(!file_exists($realFilePath)) {
            throw new \Exception(sprintf("File %s does not exist", $realFilePath));
        }
        $request = new Request(
            $this->resource->getLink("files")->getHref(),
            'POST',
            ["file"=> fopen($realFilePath, 'rb')],
            array('Content-Type => multipart/form-data')
        );
        $response = $this->getClient()->sendRequest($request);
        $file = new File($response->getState(), $response->getAllLinks());
        return $file;
    }

}