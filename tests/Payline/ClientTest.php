<?php
namespace Payline\Tests;

use Payline\Http;
use Payline\Http\Auth;
use Payline\Hal;
use Payline\Hal\Client;
use Payline\Hal\Exception;

class ClientTest extends \PHPUnit_Framework_TestCase {
    const APIURL = 'https://api-test.payline.io';
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