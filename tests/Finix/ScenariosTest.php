<?php
namespace Finix\Tests;


use Finix\Resources\Authorization;
use Finix\Resources\Identity;
use Finix\Resources\PaymentInstrument;
use Finix\Resources\Transfer;

class ScenariosTest extends \PHPUnit_Framework_TestCase {

    public function test_Scenario()
    {
        $identity = $this->createIdentity();
        $this->createMerchant($identity);
        $card = $this->createCard($identity);
        $bank = $this->createBank($identity);

        // auth + capture
        $auth = $this->createAuthorization($identity, $card);
        $captured = $this->captureAuthorization($auth);

        // create sale directly
        $srcTxfr = $this->createSourceTransfer($identity, $card);

        // credit a bank account
        $destTxfr = $this->createDestinationTransfer($identity, $bank);

        // reverse a sale ("issue a refund")
        $reversal = $this->reverseTransfer($srcTxfr);
    }

    /**
     * @return \Finix\Resources\Identity
     */
    private function createIdentity()
    {
        $IDENTITY_PAYLOAD = <<<TAG
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
        $state = json_decode($IDENTITY_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
        $identity = new Identity($state);
        return $identity->save();
    }

    /**
     * @param \Finix\Resources\Identity $identity
     * @return \Finix\Resources\Merchant
     */
    private function createMerchant($identity)
    {
        $payload = array(
            "processor" => "DUMMY_V1");
        return $identity->provisionMerchantOn($payload);
    }

    private function createCard($identity)
    {
        $PAYMENT_CARD_PAYLOAD = <<<TAG
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
        $state = json_decode($PAYMENT_CARD_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
        $state['identity'] = $identity->id;
        $card = new PaymentInstrument($state);
        return $card->save();
    }

    private function createBank($identity)
    {
        $BANK_ACCOUNT_PAYLOAD = <<<TAG
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
        $state = json_decode($BANK_ACCOUNT_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
        $state['identity'] = $identity->id;
        $bank = new PaymentInstrument($state);
        return $bank->save();
    }

    private function createAuthorization($identity, $card)
    {
        $state = [
            "processor" => "DUMMY_V1",
            "amount" => 100,
            "merchant_identity" => $identity->id,
            "source" => $card->id,
            "tags" => [
                "name" => "a random test name"
            ]
        ];
        $auth = new Authorization($state);
        return $auth->save();
    }

    /**
     * @param \Finix\Resources\Authorization $auth
     * @return \Finix\Resources\Authorization
     */
    private function captureAuthorization($auth)
    {
        $auth->capture_amount = 50;
        return $auth->save();
    }

    private function createSourceTransfer($identity, $card)
    {
        $state = [
            "processor" => "DUMMY_V1",
            "amount" => 1000,
            "currency" => "USD",
            "merchant_identity" => $identity->id,
            "source" => $card->id
        ];
        $srcTxfr = new Transfer($state);
        return $srcTxfr->save();
    }

    private function createDestinationTransfer($identity, $bank)
    {
        $state = [
            "processor" => "DUMMY_V1",
            "amount" => 1000,
            "currency" => "USD",
            "merchant_identity" => $identity->id,
            "destination" => $bank->id
        ];
        $destTxfr = new Transfer($state);
        return $destTxfr->save();
    }

    /**
     * @param \Finix\Resources\Transfer $srcTxfr
     * @return \Finix\Resources\Reversal
     */
    private function reverseTransfer($srcTxfr)
    {
        return $srcTxfr->reverse(100);
    }

}