<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Resources\Webhook;

class WebhooktTest extends \PHPUnit_Framework_TestCase
{

    const WEBHOOK_PAYLOAD = <<<TAG
    {
        "url": "https://www.example_google.com/"
    }
TAG;

    protected $webhook;

    public function setUp() {
        $this->webhook = json_decode(self::WEBHOOK_PAYLOAD, true);
        $this->assertEquals(json_last_error(), 0);
    }

    public function test_webhookCreate() {
        $webhook = new Webhook($this->webhook);
        $webhook->save();
        $this->assertTrue(isset($webhook->id));

        // check fields
        $this->assertEquals($webhook->url, $this->webhook['url']);
    }

    public function test_retrievePaymentInstrument() {
        $webhook = (new Webhook($this->webhook))->save();

        $retrievedWebhook= Webhook::retrieve($webhook->id);
        $this->assertEquals($retrievedWebhook->id, $webhook->id);
    }
}
