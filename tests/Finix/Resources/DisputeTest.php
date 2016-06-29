<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Dispute;
use Finix\Resources\Identity;
use Finix\Resources\PaymentInstrument;
use Finix\Resources\Transfer;

class DisputeTest extends \PHPUnit_Framework_TestCase
{

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
        "amount": 888888,
        "currency": "USD",
        "processor": "DUMMY_V1"
    }
TAG;
    protected static $identity;
    protected static $bank;
    protected static $card;

    protected $transfer;
    protected $receiptImage;

    public static function setUpBeforeClass()
    {
        // TODO: identity must have a merchant account on DUMMY_V1 processor
        self::$identity = Identity::retrieve('ID6ue3SPmAZBQg1rLkrtqnkZ');

        // setup card
        $card = json_decode(self::PAYMENT_CARD_PAYLOAD, true);
        $card['identity'] = self::$identity->id;
        $card = new PaymentInstrument($card);
        $card->save();
        self::$card = $card;
    }

    public function setUp() {
        $this->receiptImage = realpath("../../data/receipt.jpg");
        $this->transfer = json_decode(self::TRANSFER_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    private function fillSourceTransfer($transfer)
    {
        $transfer['identity'] = self::$identity->id;
        $transfer['source'] = self::$card->id;
        return $transfer;
    }

    public function disputedTransferCreateFromPaymentCard() {
        $transfer_state = $this->fillSourceTransfer($this->transfer);
        $transfer = new Transfer($transfer_state);
        $transfer->save();
        $this->assertStringStartsWith('TR', $transfer->id);
        $this->assertEquals($transfer->state, 'PENDING'); // transfers are async
    }

    public function test_disputeRetrieve() {
        $dispute = Dispute::retrieve("DI38iqAaFZie49p5PQv9f3eU");
        $this->assertEquals("DI38iqAaFZie49p5PQv9f3eU", $dispute->id);
//        $this->assertEquals($dispute->reason, "FRAUD");
    }

    public function test_uploadDispute() {
        $dispute = Dispute::retrieve("DI38iqAaFZie49p5PQv9f3eU");
        /** @var Dispute $dispute */
        $file = $dispute->uploadEvidence($this->receiptImage);
        $this->assertStringStartsWith("DF", $file->id);
        $this->assertEquals($file->dispute, $dispute->id);
    }

}
