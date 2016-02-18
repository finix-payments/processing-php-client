<?php
namespace Finix\Resources;

use Finix\Hal\HrefSpec;
use Finix\Http\MultipartBody;
use Finix\Http\Request;
use Finix\Resource;
use GuzzleHttp\Post\PostFile;

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
            array(),
            new MultipartBody(new PostFile("file", $realFilePath, 'rb'))
        );
        $response = $this->getClient()->sendRequest($request);
        $file = new File($response->getState(), $response->getAllLinks());
        return $file;
    }

}