<?php
require_once('vendor/autoload.php');

require(__DIR__ . '/src/Finix/Settings.php');
Finix\Settings::configure('http://b.papi.staging.finix.io', 'USwyuGJdVcsRTzDeX9smLVGQ', '968cb207-1abb-4100-9425-9a723e99eb10');
require(__DIR__ . '/src/Finix/Bootstrap.php');
\Finix\Bootstrap::init();


// CREATE IDENTITY
var_dump("// CREATE IDENTITY");
use Finix\Resources\Identity;
//$payload = <<<TAG
//    {
//        "entity": {
//            "business_type": "LIMITED_LIABILITY_COMPANY",
//            "business_phone": "+1 (408) 756-4497",
//            "first_name": "dwayne",
//            "last_name": "saget",
//            "dob": {
//                "month": 5,
//                "day": 27,
//                "year": 1978
//            },
//            "business_address": {
//                "city": "San Mateo",
//                "country": "USA",
//                "region": "CA",
//                "line2": "Apartment 8",
//                "line1": "741 Douglass St",
//                "postal_code": "94114"
//            },
//            "doing_business_as": "doingBusinessAs",
//            "phone": "1234567890",
//            "personal_address": {
//                "city": "San Mateo",
//                "country": "USA",
//                "region": "CA",
//                "line2": "Apartment 7",
//                "line1": "741 Douglass St",
//                "postal_code": "94114"
//            },
//            "business_name": "business inc",
//            "business_tax_id": "123456789",
//            "email": "user@example.org",
//            "tax_id": "5779"
//        }
//    }
//TAG;

//$identity_info = json_decode($payload, true);
$identity = new Identity(array(
    "entity"=> array(
        "business_type"=> "LIMITED_LIABILITY_COMPANY",
        "business_phone"=> "+1 (408) 756-4497",
        "first_name"=> "dwayne",
        "last_name"=> "saget",
        "dob"=> array(
            "month"=> 5,
            "day"=> 27,
            "year"=> 1978
        ),
        "business_address"=> array(
            "city"=> "San Mateo",
            "country"=> "USA",
            "region"=> "CA",
            "line2"=> "Apartment 8",
            "line1"=> "741 Douglass St",
            "postal_code"=> "94114"
        ),
        "doing_business_as"=> "doingBusinessAs",
        "phone"=> "1234567890",
        "personal_address"=> array(
            "city"=> "San Mateo",
            "country"=> "USA",
            "region"=> "CA",
            "line2"=> "Apartment 7",
            "line1"=> "741 Douglass St",
            "postal_code"=> "94114"
        ),
        "business_name"=> "business inc",
        "business_tax_id"=> "123456789",
        "email"=> "user@example.org",
        "tax_id"=> "5779"
    )
));
$identity = $identity->save();
var_dump($identity);

//RETRIEVE IDENTITY
var_dump("//RETRIEVE IDENTITY");

$identity = Identity::retrieve($identity->id);
var_dump($identity);

//UNDERWRITE AN IDENTITY
var_dump("//UNDERWRITE AN IDENTITY");

$identity = Identity::retrieve($identity->id);
$merchant = $identity->provisionMerchantOn("DUMMY_V1");
var_dump($merchant);


// LIST ALL IDENTITIES
var_dump("// LIST ALL IDENTITIES");

// MERCHANTS
var_dump("// MERCHANTS");
// Retrieve merchants
var_dump("// Retrieve merchants");
use Finix\Resources\Merchant;
$merchant = Merchant::retrieve($merchant->id);
var_dump($merchant);


//Payment Instruments
var_dump("//Payment Instruments");
//create card
var_dump("//create card");
use Finix\Resources\PaymentInstrument;
//$payload = <<<TAG
//        {
//            "name": {
//                "first_name": "Joe",
//                "last_name": "Doe",
//                "full_name": "Joe Doe"
//            },
//            "type": "PAYMENT_CARD",
//            "tags": null,
//            "expiration_month": 12,
//            "expiration_year": 2017,
//            "number": "4111 1111 1111 1111",
//            "security_code": "231",
//            "address": null
//        }
//TAG;
//$payload = json_decode($payload, true);
//$payload['identity'] = $identity->id;
$card = new PaymentInstrument(array(
    "name"=> array(
        "first_name"=> "Joe",
        "last_name"=> "Doe",
        "full_name"=> "Joe Doe"
    ),
    "type"=> "PAYMENT_CARD",
    "tags"=> null,
    "expiration_month"=> 12,
    "expiration_year"=> 2017,
    "number"=> "4111 1111 1111 1111",
    "security_code"=> "231",
    "address"=> null,
    "identity" => $identity->id,
    ));
$card = $card->save();
var_dump($card);


//create bank account
var_dump("//create bank account");
//$payload = <<<TAG
//        {
//            "name": {
//                "first_name": "dwayne",
//                "last_name": "saget",
//                "full_name": "dwayne saget"
//            },
//            "type": "BANK_ACCOUNT",
//            "tags": null,
//            "account_number": "84012312415",
//            "bank_code": "840123124",
//            "account_type": "SAVINGS",
//            "iban": null,
//            "bic": null,
//            "company_name": "company name",
//            "country": "USA",
//            "currency": "USD",
//            "available_account_type": null
//        }
//TAG;
//$payload = json_decode($payload, true);
//var_dump("HERE-------------------------------------");
//var_dump($payload);
//$payload['identity'] = $identity->id;
$bank = new PaymentInstrument(array(
    "name"=> array(
        "first_name"=> "Joe",
        "last_name"=> "Doe",
        "full_name"=> "Joe Doe"
    ),
    "type"=> "BANK_ACCOUNT",
    "tags"=> null,
    "account_number"=> "84012312415",
    "bank_code"=> "840123124",
    "account_type"=> "SAVINGS",
    "iban"=> null,
    "bic"=> null,
    "company_name"=> "company name",
    "country"=> "USA",
    "currency"=> "USD",
    "available_account_type"=> null,
    "identity" => $identity->id,
    ));
$bank = $bank->save();
var_dump($bank);

// Retrieve merchants
var_dump("// Retrieve merchants");
$merchant = PaymentInstrument::retrieve($card->id);
var_dump($card);


// Card Authorization
var_dump("// Card Authorization");
// Create new auth
var_dump("// Create new auth");
use Finix\Resources\Authorization;

$payload = array(
    "processor" => "DUMMY_V1",
    "amount" => 100,
    "merchant_identity" => $identity->id,
    "source" => $card->id,
    "tags" => array("name" => "a random test name"));
$auth = new Authorization($payload);
$auth = $auth->save();
var_dump($auth);

// retrieve an auth
var_dump("// retrieve an auth");
$auth = Authorization::retrieve($auth->id);

// capture an auth
var_dump("// capture an auth");
$auth = Authorization::retrieve($auth->id);
$auth->capture_amount = 50;
$response = $auth->save();
var_dump($response);


// Transafers
var_dump("// Transafers");
// Create new card debit
var_dump("// Create new card debit");
use Finix\Resources\Transfer;
$payload = array(
    "processor" => "DUMMY_V1",
    "amount" => 1000,
    "currency" => "USD",
    "identity" => $identity->id,
    "source" => $card->id);
$debit = new Transfer($payload);
$debit = $debit->save();
var_dump($debit);

// Credit a Bank Account
var_dump("// Credit a Bank Account");
$payload = array(
    "processor" => "DUMMY_V1",
    "amount" => 1000,
    "currency" => "USD",
    "identity" => $identity->id,
    "destination" => $bank->id);
$credit = new Transfer($payload);
$credit = $credit->save();
var_dump($credit);

// retrieve a transfer
var_dump("// retrieve a transfer");
$debit = Transfer::retrieve($debit->id);
var_dump($debit);

// refund a debit
var_dump("// refund a debit");
$debit = Transfer::retrieve($debit->id);
$refund = $debit->reverse(100);
var_dump($refund);

// DISPUTES
var_dump("// DISPUTES");
//use Finix\Resources\Disputes;