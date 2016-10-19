<?php
namespace Finix\Tests;

use Finix\Http\Auth;
use Finix\Hal\Client;

class ClientTest extends \PHPUnit_Framework_TestCase {
    /** @var $client Client */
    protected $client;

    protected function setUp() {
        $this->client = new Client(
            Fixtures::$apiUrl,
            '/',
            Fixtures::$apiUrl,
            new Auth\BasicAuthentication(Fixtures::$username, Fixtures::$password)
        );
    }

    public function testClientCommunicatesToAPI() {
        $this->assertNotNull($this->client->getEntryPointResource());
    }

}
