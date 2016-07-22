<?php
namespace Finix\Test;

class MerchantTest extends \PHPUnit_Framework_TestCase
{

    const MERCHANT_PAYLOAD = <<<TAG
    {
       "tags": {
           "name": "test-merchant"
       },
       "processor": "DUMMY_V1"
    }
TAG;
    protected $state;

    public function setUp() {
        $this->state = json_decode(self::MERCHANT_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    public function test_merchantCreate() {

    }

}
