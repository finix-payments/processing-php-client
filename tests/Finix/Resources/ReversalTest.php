<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Identity;
use Finix\Resources\PaymentInstrument;
use Finix\Resources\Reversal;
use Finix\Resources\Transfer;

class ReversalTest extends \PHPUnit_Framework_TestCase
{
    const REVERSAL_PAYLOAD = <<<TAG
    {
       "refund_amount": 100
    }
TAG;

    const PAYMENT_CARD_PAYLOAD = <<<TAG
    {
       "name": {
           "first_name": "Joe",
           "last_name": "Doe",
           "full_name": "Joe Doe"
       },
       "type": "PAYMENT_CARD",
       "tags": null,
       "expiration_month": 12,
       "expiration_year": 2017,
       "number": "4111 1111 1111 1111",
       "security_code": "231",
       "address": null
    }
TAG;
    const TRANSFER_PAYLOAD = <<<TAG
    {
        "amount": 1000,
        "currency": "USD",
        "processor": "DUMMY_V1"
    }
TAG;

    protected static $identity;
    protected static $card;

    protected $reversal;
    protected $transfer;

    public static function setUpBeforeClass()
    {
        // TODO: identity must have a merchant account on DUMMY_V1 processor
        self::$identity = Identity::retrieve('IDjBjyepZh7pqVU1B3si4aD3');

        // setup card
        $card = json_decode(self::PAYMENT_CARD_PAYLOAD, true);
        $card['identity'] = self::$identity->id;
        $card = new PaymentInstrument($card);
        $card->save();
        self::$card = $card;
    }

    public function setUp() {
        $this->transfer = json_decode(self::TRANSFER_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
        $this->reversal = json_decode(self::REVERSAL_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    private function fillSourceTransfer($transfer)
    {
        $transfer['identity'] = self::$identity->id;
        $transfer['source'] = self::$card->id;
        return $transfer;
    }

    public function test_reversalCreate()
    {
        $transfer_state = $this->fillSourceTransfer($this->transfer);
        $transfer = new Transfer($transfer_state);
        $transfer->save();
        print_r($transfer);
    }



}
