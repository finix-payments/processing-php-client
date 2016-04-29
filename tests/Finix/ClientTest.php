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

    const USERNAME = 'USrdogpiqwJFdUFEAqyzBXJu';
    const PASSWORD = '45c9f61e-fb31-4004-9938-7827baf3e652';

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
