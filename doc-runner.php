<?php
require_once('vendor/autoload.php');
require(__DIR__ . '/src/Finix/Settings.php');
Finix\Settings::configure('http://b.papi.staging.finix.io', 'USwyuGJdVcsRTzDeX9smLVGQ', '968cb207-1abb-4100-9425-9a723e99eb10');
require(__DIR__ . '/src/Finix/Bootstrap.php');
\Finix\Bootstrap::init();

use Finix\Resources\Identity;

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