<?php
namespace Finix\Tests;

use Finix\Http;
use Finix\Http\Auth;
use Finix\Hal;
use Finix\Hal\Client;
use Finix\Hal\Exception;

class ClientTest extends \PHPUnit_Framework_TestCase {
    const APIURL = 'http://b.papi.staging.finix.io';
    const PROFILEURL = self::APIURL;

    const USERNAME = 'USwyuGJdVcsRTzDeX9smLVGQ';
    const PASSWORD = '968cb207-1abb-4100-9425-9a723e99eb10';

    /** @var $client Client */
    protected $client;

    protected function setUp() {
        $this->client = new Client(
            self::APIURL,
            '/',
            self::PROFILEURL,
            new Auth\BasicAuthentication(self::USERNAME, self::PASSWORD)
        );
    }

    public function test_clientCommunicatesToAPI() {
        $this->assertNotNull($this->client->getEntryPointResource());
    }

}