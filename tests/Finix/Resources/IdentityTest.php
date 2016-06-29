<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Identity;

class IdentityTest extends \PHPUnit_Framework_TestCase
{

    const IDENTITY_PAYLOAD = <<<TAG
    {
        "entity": {
            "max_transaction_amount": 100,
            "url": "http://sample-url.com",
            "annual_card_volume": 100,
            "default_statement_descriptor": "default statement",
            "incorporation_date": {"day": 12, "month": 2, "year": 2016},
            "mcc": 7399,
            "principal_percentage_ownership": 12,
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
    protected $state;

    public function setUp() {
        $this->state = json_decode(self::IDENTITY_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    public function test_createIdentity() {
        $identity = new Identity($this->state);
        $this->assertFalse(isset($identity->id));
        $identity->save();
        $this->assertTrue(isset($identity->id));
        $this->assertStringStartsWith('ID', $identity->id);
    }

    public function test_retrieveIdentity() {
        $identity = new Identity($this->state);
        $identity->save();

        $fetchedIdentity = Identity::retrieve($identity->id);
        $this->assertStringEndsWith($identity->id, $fetchedIdentity->getHref());
    }

    public function test_updateIdentity() {
        $this->markTestSkipped("Ignored until Identity can be updated");

        $identity = new Identity($this->state);
        $identity->save();
        $this->assertEquals($identity->entity['tax_id'], $this->state['entity']['tax_id']);
        $entity = $identity->entity;
        $entity['tax_id'] = '991111';
        $identity->entity = $entity;
        $identity->save();
        $this->assertEquals($identity->entity['tax_id'], '991111');
    }
}
