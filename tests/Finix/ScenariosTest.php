<?php
namespace Finix\Tests;


use Finix\Resources\Dispute;
use Finix\Resources\Verification;
use Finix\Resources\PaymentCard;
use Finix\Resources\BankAccount;
use Finix\Resources\User;
use Finix\Settings;

class ScenariosTest extends \PHPUnit_Framework_TestCase
{

    private $partnerUser;
    private $user;
    private $application;
    private $dummyProcessor;
    private $identity;
    private $merchant;
    private $card;
    private $receiptImage;

    protected function setUp()
    {
        $this->receiptImage = realpath("../../data/receipt.jpg");

        date_default_timezone_set("UTC");
        Settings::configure(["username" => null, "password" => null]);

        $this->user = Fixtures::createAdminUser();

        Settings::configure(["username" => $this->user->id, "password" => $this->user->password]);

        $this->application = Fixtures::createApplication($this->user);
        $this->application->processing_enabled = true;
        $this->application->settlement_enabled = true;
        $this->application = $this->application->save();

        $this->dummyProcessor = Fixtures::dummyProcessor($this->application);

        $this->partnerUser = Fixtures::createPartnerUser($this->application);

        Settings::configure(["username" => $this->partnerUser->id, "password" => $this->partnerUser->password]);

        $this->identity = Fixtures::createIdentity();

//        $entity = $this->identity->entity;
//        $entity["last_name"] = "serna";
//        $this->identity->entity = $entity;
//        $this->identity->save();

        $this->merchant = Fixtures::provisionMerchant($this->identity);

        $this->card = Fixtures::createCard($this->identity);
    }

    public function testCreateMerchantUser() {
        $this->markTestSkipped("https://github.com/verygoodgroup/api-spec/issues/333");
        $user = $this->identity->createMerchantUser(new User([["enabled" => true]]));
        self::assertNotNull($user->id);
    }

    public function testCreateBankAccountDirectly() {
        $bankAccount = new BankAccount(
            array(
                "account_type"=> "SAVINGS",
                "name"=> "Fran Lemke",
                "tags"=> array(
                    "Bank Account"=> "Company Account"
                ),
                "country"=> "USA",
                "bank_code"=> "123123123",
                "account_number"=> "123123123",
                "type"=> "BANK_ACCOUNT",
                "identity"=> $this->identity->id
            ));
        $bankAccount = $bankAccount->save();
        self::assertNotNull($bankAccount->id, "Invalid bank account");
    }

    public function testCreatePaymentCardDirectly() {
        $card = new PaymentCard([
            "name" => "Joe Doe",
            "expiration_month" => 12,
            "expiration_year" => 2030,
            "number" => "4111 1111 1111 1111",
            "security_code" => 231,
            "identity"=> $this->identity->id
        ]);
        $card = $card->save();
        self::assertNotNull($card->id, "Invalid card");
    }

    public function testCreateWebhook() {
        $webhook = Fixtures::createWebhook("https://tools.ietf.org/html/rfc2606");
        self::assertNotNull($webhook->id);
    }

    public function testCreateToken() {
        $token = Fixtures::createPaymentToken($this->application, $this->identity->id);
        self::assertNotNull($token->id, "Payment token not created");
    }

    public function testCreateBankAccount() {
        $bankAccount = Fixtures::createBankAccount($this->identity);
        self::assertNotNull($bankAccount->id);
    }

    public function testVerifyIdentity() {
        $verification = $this->identity->verifyOn(new Verification(["processor" => "DUMMY_V1"]));
        self::assertEquals($verification->state, "PENDING");
    }

    public function testDebitTransfer() {
        $transfer = Fixtures::createTransfer([
            "identity" => $this->card->identity,
            "amount" => Fixtures::$disputeAmount,
            "source" => $this->card->id,
            "tags" => ["_source" => "php_client"]
        ]);
        self::assertEquals($transfer->state, "PENDING", "Transfer not in pending state");
        Fixtures::waitFor(function () use ($transfer) {
            $transfer->refresh();
            return $transfer->state == "SUCCEEDED";
        });
        return $transfer;
    }

    public function testCaptureAuthorization()
    {
        $authorization = Fixtures::createAuthorization($this->card, 100);
        $authorization = $authorization->capture([
            "capture_amount"=> 100,
            "fee"=> 10
        ]);
        self::assertEquals($authorization->state, "SUCCEEDED", "Capture amount $10 of '" . $this->card->id . "' not succeeded");
    }

    public function testReverseFunds()
    {
        $transfer = $this->testDebitTransfer();
        $transfer = $transfer->reverse(50);
        self::assertEquals($transfer->state, "PENDING", "Reverse not in pending state");
    }

    public function testVoidAuthorization()
    {
        $authorization = Fixtures::createAuthorization($this->card, 100);
        $authorization = $authorization->void(true);
        self::assertTrue($authorization->is_void, "Authorization not void");
    }

    public function testSettlement()
    {
        $transfer1 = Fixtures::createTransfer([
            "identity" => $this->card->identity,
            "amount" => 500,
            "source" => $this->card->id
        ]);

        $transfer2 = Fixtures::createTransfer([
            "identity" => $this->card->identity,
            "amount" => 300,
            "source" => $this->card->id
        ]);

        Fixtures::waitFor(function () use ($transfer1, $transfer2) {
            $transfer1 = $transfer1->refresh();
            $transfer2 = $transfer2->refresh();

            return $transfer1->state == "SUCCEEDED" and
                $transfer2->state == "SUCCEEDED" and
                $transfer1->ready_to_settle_at != null and
                $transfer2->ready_to_settle_at != null;
        });

        $settlement = Fixtures::createSettlement($this->identity);
        $this->assertNotNull($settlement->id);
    }

    public function testDispute() {
        $transfer = $this->testDebitTransfer();
        $disputePage = Dispute::getPagination($transfer->getHref("disputes"));
        $dispute = $disputePage->items[0];
        $file = $dispute->uploadEvidence($this->receiptImage);
        $this->assertEquals($file->dispute, $dispute->id);
    }
}
