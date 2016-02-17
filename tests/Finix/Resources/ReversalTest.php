<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Identity;

class ReversalTest extends \PHPUnit_Framework_TestCase
{

    const REVERSAL_PAYLOAD = <<<TAG
    {
       "refund_amount": 100
    }
TAG;

    protected $state;

    public function setUp() {
        $this->state = json_decode(self::REVERSAL_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    public function test_reversalCreate() {

    }



}
