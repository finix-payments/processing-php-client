<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Identity;
use Finix\Resources\PaymentInstrument;
use Finix\Resources\Transfer;

class TransferTest extends \PHPUnit_Framework_TestCase
{

    const BANK_ACCOUNT_PAYLOAD = <<<TAG
    {
        "name": {
            "first_name": "dwayne",
            "last_name": "Sunkhronos",
            "full_name": "dwayne Sunkhronos"
        },
        "type": "BANK_ACCOUNT",
        "tags": null,
        "account_number": "84012312415",
        "bank_code": "840123124",
        "account_type": "SAVINGS",
        "iban": null,
        "bic": null,
        "company_name": "company name",
        "country": "USA",
        "currency": "USD",
        "available_account_type": null
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
    protected static $bank;
    protected static $card;

    protected $transfer;

    public static function setUpBeforeClass()
    {
        // TODO: identity must have a merchant account on DUMMY_V1 processor
        self::$identity = Identity::retrieve('ID69aYzr1DErerPLzjyASrCn');

        // setup bank account
        $bank = json_decode(self::BANK_ACCOUNT_PAYLOAD, true);
        $bank['identity'] = self::$identity->id;
        $bank = new PaymentInstrument($bank);
        $bank->save();
        self::$bank = $bank;

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
    }

     private function fillDestinationTransfer($transfer)
    {
        $transfer['merchant_identity'] = self::$identity->id;
        $transfer['destination'] = self::$bank->id;
        return $transfer;
    }

    private function fillSourceTransfer($transfer)
    {
        $transfer['merchant_identity'] = self::$identity->id;
        $transfer['source'] = self::$card->id;
        return $transfer;
    }

    public function test_transferCreateToBankAccount() {
        $transfer_state = $this->fillDestinationTransfer($this->transfer);
        $transfer = new Transfer($transfer_state);
        $transfer->save();
        $this->assertStringStartsWith('TR', $transfer->id);
        $this->assertEquals($transfer->state, 'PENDING'); // transfers are async
    }

    public function test_transferCreateFromPaymentCard() {
        $transfer_state = $this->fillSourceTransfer($this->transfer);
        $transfer = new Transfer($transfer_state);
        $transfer->save();
        $this->assertStringStartsWith('TR', $transfer->id);
        $this->assertEquals($transfer->state, 'PENDING'); // transfers are async
    }

public function test_reversalCreate()
    {
        $transfer_state = $this->fillSourceTransfer($this->transfer);
        $transfer = new Transfer($transfer_state);
        $transfer->save();
        $reversal = $transfer->reverse(100);
        // reversals are transfers in opp. direction, so are also async
        $this->assertEquals($reversal->state, 'PENDING');
        $this->assertStringEndsWith($transfer->id, $reversal->getParentTransferHref());
    }
}
