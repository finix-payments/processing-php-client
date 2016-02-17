<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Identity;

class TransferTest extends \PHPUnit_Framework_TestCase
{

    const TRANSFER_PAYLOAD = <<<TAG
    {
        "destination": null,
        "amount": 100,
        "currency": "USD",
        "tags": {},
        "processor": "DUMMY_V1"
    }
TAG;

    protected $state;

    public function setUp() {
        $this->state = json_decode(self::TRANSFER_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    public function test_transferCreate() {

    }



}
