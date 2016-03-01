<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Identity;
use Finix\Resources\PaymentInstrument;
use Finix\Resources\Transfer;
use Finix\Resources\Settlement;

class SettlementTest extends \PHPUnit_Framework_TestCase
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
        "amount": 1000,
        "currency": "USD",
        "processor": "DUMMY_V1"
    }
TAG;


    const IDENTITY_PAYLOAD = <<<TAG
    {
        "entity": {
            "business_type": "LIMITED_LIABILITY_COMPANY",
            "business_phone": "+1 (408) 756-4497",
            "first_name": "dwayne",
            "last_name": "Sunkhronos",
            "dob": {
                "month": 5,
                "day": 27,
                "year": 1978
            },
            "business_address": {
                "city": "San Mateo",
                "country": "USA",
                "region": "CA",
                "line2": "Apartment 8",
                "line1": "741 Douglass St",
                "postal_code": "94114"
            },
            "doing_business_as": "doingBusinessAs",
            "phone": "1234567890",
            "personal_address": {
                "city": "San Mateo",
                "country": "USA",
                "region": "CA",
                "line2": "Apartment 7",
                "line1": "741 Douglass St",
                "postal_code": "94114"
            },
            "business_name": "business inc",
            "business_tax_id": "123456789",
            "email": "user@example.org",
            "tax_id": "5779"
        }
    }
TAG;


    const SETTLEMENT_PAYLOAD = <<<TAG
    {
        "identity": "null",
        "processor": "DUMMY_V1",
        "currency": "USD"
    }
TAG;

    protected static $identity;
    protected static $card;
    protected $settlement;
    protected $state;

    public static function setUpBeforeClass()
    {
        $identity = json_decode(self::IDENTITY_PAYLOAD, true);
        $identity = new Identity($identity);
        self::$identity->save();
        $identity->provisionMerchantOn("DUMMY_V1");

        $card = json_decode(self::PAYMENT_CARD_PAYLOAD, true);
        $card['identity'] = $identity->id;
        $card = new PaymentInstrument($card);
        $card->save();
        self::$card = $card;

        $transfer = json_decode(self::TRANSFER_PAYLOAD, true);
        $transfer = new Transfer($transfer);
        $transfer->save();
        self::$transfer= $transfer;
    }

    public function setUp() {
        $this->settlement = json_decode(self::SETTLEMENT_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    private function fillSettlement($settlement)
    {
        $settlement['identity'] = self::$identity->id;
        return $settlement;
    }

    public function test_createSettlement() {
        sleep(60);
        $settlement_state = $this->fillSettlement($this->$settlement);
        $settlement = self::$identity->createSettlement($settlement_state);
        $this->assertStringStartsWith('ST', $settlement->id);
        $this->assertEquals($settlement->identity, self::$identity->id);
        $this->assertEquals($settlement->transfer, null);
    }
}