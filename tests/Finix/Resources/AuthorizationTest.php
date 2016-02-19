<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Authorization;
use Finix\Resources\Identity;
use Finix\Resources\PaymentInstrument;

class AuthorizationTest extends \PHPUnit_Framework_TestCase
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

    const AUTHORIZATION_PAYLOAD = <<<TAG
    {
       "tags": {
           "name": "test-merchant"
       },
       "processor": "DUMMY_V1",
       "amount": 100
    }
TAG;

    const IDENTITY_PAYLOAD = <<<TAG
    {
        "entity": {
            "business_type": "LIMITED_LIABILITY_COMPANY",
            "business_phone": "+1 (408) 756-4497",
            "first_name": "dwayne",
            "last_name": "saget",
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
    protected $state;
    protected static $identity;
    protected static $card;
    protected $auth;

    public static function setUpBeforeClass()
    {
        $state = json_decode(self::IDENTITY_PAYLOAD, true);
        $identity = new Identity($state);
        self::$identity = $identity->save();merchant account on DUMMY_V1 processor
        // TODO: if we're calling the api with wrong credentials, we get 500 instead of 403
        $card = json_decode(self::PAYMENT_CARD_PAYLOAD, true);
        $card['identity'] = self::$identity->id;
        $card = new PaymentInstrument($card);
        $card->save();
        self::$card = $card;
    }

    public function setUp() {
        $this->auth = json_decode(self::AUTHORIZATION_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);


    }

    private function fillAuthorization($auth)
    {
        $auth['merchant_identity'] = self::$identity->id;
        $auth['source'] = self::$card->id;
        return $auth;
    }

    public function test_createAuthorization() {
        $auth_state = $this->fillAuthorization($this->auth);
        $auth = new Authorization($auth_state);
        $auth->save();
        $this->assertStringStartsWith('AU', $auth->id);
        $this->assertEquals($auth->state, 'SUCCEEDED');
    }

    public function test_updateAuthorization() {
        $auth_state = $this->fillAuthorization($this->auth);
        $auth = new Authorization($auth_state);
        $auth->save();
        $old_id = $auth->id;
        $auth->capture_amount = 50;
        $auth->save();
        $this->assertEquals($old_id, $auth->id);
        $this->assertEquals($auth->state, 'SUCCEEDED');
        $this->assertNotNull($auth->transfer);
    }
}
