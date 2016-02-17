<?php

namespace Finix\Hal;

/**
 * The Extension Relation Type described in:
 * - section 8.2 of the HAL specification
 * - section 4 of the RFC5988 - Web Linking document
 *
 * @see https://tools.ietf.org/html/draft-kelly-json-hal-07#section-8.2
 * @see https://tools.ietf.org/html/rfc5988#section-4
 */
class CustomRel
{
    /**
     * @var string
     */
    private $name;

     /**
     * An Extension Relation Type SHOULD be a URI or a name
     * using the CURIE syntax (prefix:reference).
     * Any name as a string is accepted too.
     * @param $name        string        The relation name.
     */
     public function __construct($name) {
        $name = trim($name);
        if (!$name) {
            throw new \InvalidArgumentException("The name can't be empty");
        }

        $this->name = $name;
    }

    /**
     * @return        string        The relation name.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return        string        The relation name.
     */
    public function __toString() {
        return $this->name;
    }
}
