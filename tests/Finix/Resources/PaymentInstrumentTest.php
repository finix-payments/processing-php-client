<?php
namespace Finix\Test;

use Finix\Resources\Identity;
use Finix\Resources\PaymentInstrument;
use Finix\Tests\SampleData;

class PaymentInstrumentTest extends \PHPUnit_Framework_TestCase
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
    protected $card;
    protected $bank;
    protected static $identity;

    public static function setUpBeforeClass()
    {
        // TODO: fetch this from the API via collection
        self::$identity = Identity::retrieve(SampleData::$identityId);
    }

    public function setUp() {
        $this->card = json_decode(self::PAYMENT_CARD_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
        $this->bank = json_decode(self::BANK_ACCOUNT_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    public function test_paymentCardCreate() {
        $this->card['identity'] = self::$identity->id;
        $paymentInstrument = new PaymentInstrument($this->card);
        $paymentInstrument->save();
        $this->assertTrue(isset($paymentInstrument->id));
        // security code does not come back
        $this->assertFalse(isset($paymentInstrument->security_code));

        // check fields
        $this->assertEquals($paymentInstrument->expiration_month, $this->card['expiration_month']);
        $this->assertEquals($paymentInstrument->expiration_year, $this->card['expiration_year']);
        $this->assertEquals($paymentInstrument->last_four, substr($this->card['number'], -4));
    }

    public function test_retrievePaymentInstrument() {
        $this->bank['identity'] = self::$identity->id;
        $this->card['identity'] = self::$identity->id;

        $bank = (new PaymentInstrument($this->bank))->save();
        $card = (new PaymentInstrument($this->card))->save();

        $retrievedBank= PaymentInstrument::retrieve($bank->id);
        $this->assertEquals($retrievedBank->id, $bank->id);

        $retrievedCard = PaymentInstrument::retrieve($card->id);
        $this->assertEquals($retrievedCard->id, $card->id);
    }

    public function test_bankAccountCreate() {
        $this->bank['identity'] = self::$identity->id;
        $paymentInstrument = new PaymentInstrument($this->bank);
        $paymentInstrument->save();
        print_r($paymentInstrument);
        $this->assertTrue(isset($paymentInstrument->id));

        // account number comes back as last 4
        // TODO: account number last four should come back
        //$this->assertEquals($paymentInstrument->account_number, substr($this->bank['number'], -4));

        // check fields
        $this->assertEquals($paymentInstrument->bank_code, $this->bank['bank_code']);
        $this->assertEquals($paymentInstrument->currency, $this->bank['currency']);
    }

}
