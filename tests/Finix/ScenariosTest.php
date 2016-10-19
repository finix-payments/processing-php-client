<?php
namespace Finix\Tests;


use Finix\Resources\Verification;
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
    private $cardVerification;
    private $pushFundTransfer;
    private $bankAccount;
    private $pushFundTransferReversal;
    private $webhook;
    private $authorization;
    private $identityVerification;
    private $pushFundTransfer2;
    private $pushFundTransfer1;
    private $settlement;

    protected function setUp()
    {
        Settings::configure(["username" => null, "password" => null]);

        date_default_timezone_set("UTC");

        $this->user = Fixtures::createAdminUser();

        Settings::configure(["username" => $this->user->id, "password" => $this->user->password]);

        $this->application = Fixtures::createApplication($this->user);
        $this->application->processing_enabled = true;
        $this->application->settlement_enabled = true;
        $this->application = $this->application->save();

        $this->dummyProcessor = Fixtures::dummyProcessor($this->application);

        $this->partnerUser = Fixtures::createPartnerUser($this->application);

        $this->identity = Fixtures::createIdentity();

        $this->merchant = Fixtures::provisionMerchant($this->identity);

        $this->card = Fixtures::createCard($this->identity);

        $this->cardVerification = $this->card->verifyOn(new Verification(["processor" => "DUMMY_V1"]));

        $this->pushFundTransfer = Fixtures::createTransfer([
            "identity" => $this->card->identity,
            "amount" => 500,
            "destination" => $this->card->id
        ]);

        Fixtures::waitFor(function () {
            $this->pushFundTransfer = $this->pushFundTransfer->refresh();
            return $this->pushFundTransfer->state == "SUCCEEDED";
        });

        $this->bankAccount = Fixtures::createBankAccount($this->identity);

        $this->webhook = Fixtures::createWebhook("https://tools.ietf.org/html/rfc2606");
    }

    public function testCaptureAuthorization()
    {
        Settings::configure(["username" => $this->partnerUser->id, "password" => $this->partnerUser->password]);

        $this->authorization = Fixtures::createAuthorization($this->card, 100);

        $this->authorization = $this->authorization->capture(10);
        self::assertEquals($this->authorization->state, "SUCCEEDED", "Capture amount $10 of '" . $this->card->id . "' not succeeded");
    }

    public function testReverseFunds()
    {
        $this->pushFundTransferReversal = $this->pushFundTransfer->reverse(50);
        self::assertEquals($this->pushFundTransferReversal->state, "PENDING", "Reverse not in pending state");
    }

    public function testVoidAuthorization()
    {
        $this->identityVerification = $this->identity->verifyOn(new Verification(["processor" => "DUMMY_V1"]));

        Settings::configure(["username" => $this->partnerUser->id, "password" => $this->partnerUser->password]);

        $this->authorization = Fixtures::createAuthorization($this->card, 100);
        $this->authorization = $this->authorization->setVoidMe(true);
        self::assertTrue($this->authorization->is_void, "Authorization not void");
    }

    public function testSettlement()
    {
        $this->pushFundTransfer1 = Fixtures::createTransfer([
            "identity" => $this->card->identity,
            "amount" => 500,
            "source" => $this->card->id
        ]);

        $this->pushFundTransfer2 = Fixtures::createTransfer([
            "identity" => $this->card->identity,
            "amount" => 300,
            "source" => $this->card->id
        ]);

        Fixtures::waitFor(function () {
            $this->pushFundTransfer1 = $this->pushFundTransfer1->refresh();
            $this->pushFundTransfer2 = $this->pushFundTransfer2->refresh();
            return $this->pushFundTransfer1->state == "SUCCEEDED" and
                $this->pushFundTransfer2->state == "SUCCEEDED" and
                $this->pushFundTransfer1->ready_to_settle_at != null and
                $this->pushFundTransfer2->ready_to_settle_at != null;
        });

        $this->settlement = Fixtures::createSettlement($this->identity);
    }

    public function testDispute() {

    }
}
