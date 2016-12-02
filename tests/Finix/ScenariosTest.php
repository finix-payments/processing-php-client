<?php
namespace Finix\Tests;


use Finix\Resources\Dispute;
use Finix\Resources\Transfer;
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

        $this->merchant = Fixtures::provisionMerchant($this->identity);

        $this->card = Fixtures::createCard($this->identity);

        $this->cardVerification = $this->card->verifyOn(new Verification(["processor" => "DUMMY_V1"]));

        $this->pushFundTransfer = Fixtures::createTransfer([
            "identity" => $this->card->identity,
            "amount" => Fixtures::$disputeAmount,
            "source" => $this->card->id,
            "tags" => ["_source" => "php_client"]
        ]);

        self::assertEquals($this->pushFundTransfer->state, "PENDING", "Transfer not in pending state");

        Fixtures::waitFor(function () {
            $this->pushFundTransfer = $this->pushFundTransfer->refresh();
            return $this->pushFundTransfer->state == "SUCCEEDED";
        });

        $this->bankAccount = Fixtures::createBankAccount($this->identity);

        $this->webhook = Fixtures::createWebhook("https://tools.ietf.org/html/rfc2606");
    }

    public function testCaptureAuthorization()
    {
        $this->markTestSkipped('must be revisited, see https://github.com/verygoodgroup/processing/issues/2330#issue-190787250');
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
        $this->markTestSkipped('must be revisited, see https://github.com/verygoodgroup/processing/issues/2330#issue-190787250');
        $this->identityVerification = $this->identity->verifyOn(new Verification(["processor" => "DUMMY_V1"]));
        $this->authorization = Fixtures::createAuthorization($this->card, 100);
        $this->authorization = $this->authorization->void(true);
        self::assertTrue($this->authorization->is_void, "Authorization not void");
    }

    public function testSettlement()
    {
        $this->markTestSkipped('must be revisited, ready_to_settle_at now too long to be completed');
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
        $disputePage = Dispute::getPagination($this->pushFundTransfer->getHref("disputes"));
        $dispute = $disputePage->items[0];
        $file = $dispute->uploadEvidence($this->receiptImage);
        $this->assertEquals($file->dispute, $dispute->id);
    }
}
