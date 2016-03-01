<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Identity;

class VerificationTest extends \PHPUnit_Framework_TestCase
{

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

    const VERIFICATION_PAYLOAD = <<<TAG
    {
        "processor": "DUMMY_V1"
    }
TAG;

    protected $state;
    protected $verify_payload;

    public function setUp() {
        $this->state = json_decode(self::IDENTITY_PAYLOAD, true);

        $this->verify_payload = json_decode(self::VERIFICATION_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    public function test_verifyIdentity() {
        $identity = new Identity($this->state);
        $identity->save();
        $verification = $identity->verifyOn($this->verify_payload);
        $this->assertStringStartsWith('VI', $verification ->id);
        $this->assertEquals($verification->entity["state"], "PENDING");
        $this->assertEquals($verification->entity["processor"], "DUMMY_V1");
    }
}
