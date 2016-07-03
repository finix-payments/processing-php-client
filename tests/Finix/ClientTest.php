<?php
namespace Finix\Tests;

use Finix\Http;
use Finix\Http\Auth;
use Finix\Hal;
use Finix\Hal\Client;
use Finix\Hal\Exception;

class ClientTest extends \PHPUnit_Framework_TestCase {
    const APIURL = 'https://api-staging.finix.io/';
    const PROFILEURL = self::APIURL;

    const USERNAME = 'USgbL9NMg2AxBJH4WpPLkfTY';
    const PASSWORD = '341d885e-4422-4e7f-beb5-7dfe9d7c75da';

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
