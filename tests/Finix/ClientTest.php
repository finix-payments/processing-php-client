<?php
namespace Finix\Tests;

use Finix\Http\Auth;
use Finix\Hal\Client;

class ClientTest extends \PHPUnit_Framework_TestCase {
    /** @var $client Client */
    protected $client;

    protected function setUp() {
        $this->client = new Client(
            SampleData::$apiUrl,
            '/',
            SampleData::$apiUrl,
            new Auth\BasicAuthentication(SampleData::$username, SampleData::$password)
        );
    }

    public function test_clientCommunicatesToAPI() {
        $this->assertNotNull($this->client->getEntryPointResource());
    }

}
