<?php
namespace Finix\Test;

use Finix\Hal;
use Finix\Hal\Resource;


class ResourceTest extends \PHPUnit_Framework_TestCase {
    const JSON_REPRESENTATION = <<<END
{
    "_links": {
        "self": { "href": "/orders" },
        "curies": [{
            "name": "acme",
            "href": "http://docs.acme.com/relations/{rel}",
            "templated": true
        }],
        "next": { "href": "/orders?page=2" },
        "find": { "href": "/orders{?id}", "templated": true }
    },
    "_embedded": {
        "acme:orders": [
            {
                "_links": {
                    "self": { "href": "/orders/123" },
                    "acme:basket": { "href": "/baskets/98712" },
                    "acme:customer": { "href": "/customers/7809" }
                },
                "total": 30.00,
                "currency": "USD",
                "status": "shipped"
            },
            {
                "_links": {
                    "self": { "href": "/orders/124" },
                    "acme:basket": { "href": "/baskets/97213" },
                    "acme:customer": { "href": "/customers/12369" }
                },
                "total": 20.00,
                "currency": "USD",
                "status": "processing"
            }
        ]
    },
    "currentlyProcessing": 14,
    "shippedToday": 20
}
END;

    /**
     * Test the parsing of the JSON jsonRepresentation of a resource:
     * - properties
     * - links
     * - embedded resources
     * - links in embedded resources
     */
    public function test_parseJsonRepresentation() {

        $resource = Resource::fromJson(self::JSON_REPRESENTATION);

        // Check the properties
        $this->assertEquals(14, $resource->getState()['currentlyProcessing']);
        $this->assertEquals(20, $resource->getState()['shippedToday']);

        // We got 3 links
        $this->assertEquals(4, count($resource->getAllLinks()));

        // Are links templated
        $this->assertFalse($resource->getLink(Hal\RegisteredRel::NEXT)->isTemplated());
        $this->assertTrue($resource->getLink(new Hal\CustomRel("Find"))->isTemplated()); // Capitalized on purpose

        // We got 1 embedded array of resources
        $this->assertEquals(1, count($resource->getAllEmbeddedResources()));

        // We got 2 embedded orders
        $orders = $resource->getEmbeddedResources(new Hal\CustomRel('acme:orders'));
        /** @var $orders \Finix\Hal\Resource[] */

        $this->assertEquals(2, count($orders));
        $order1 = $orders[0];
        $order2 = $orders[1];
        $order1State = $order1->getState();
        $order2State = $order2->getState();

        // Check the totals
        $total1 = $order1State['total'];
        $total2 = $order2State['total'];
        $this->assertTrue($total1 == 20 && $total2 == 30 || $total2 == 20 && $total1 == 30);

        // Check the links of the embedded orders
        $this->assertEquals(3, count($order1->getAllLinks()));

        // Check the the embedded resources of the embedded orders
        $this->assertEquals(0, count($order1->getAllEmbeddedResources()));
    }

    public function test_equalResources() {
        $resource1 = Resource::fromJson(self::JSON_REPRESENTATION);

        $resource2 = Resource::fromJson(self::JSON_REPRESENTATION);

        $this->assertTrue($resource1->getState() == $resource2->getState(),
                'States are not equal.');

        $this->assertTrue($resource1->getAllLinks() == $resource2->getAllLinks(),
                'Links are not equal.');

        $this->assertTrue($resource1->getAllEmbeddedResources() == $resource2->getAllEmbeddedResources(),
                'Embedded resources are not equal.');

        $this->assertTrue($resource1 == $resource2,
                'Resources are not equal.');
    }

}
