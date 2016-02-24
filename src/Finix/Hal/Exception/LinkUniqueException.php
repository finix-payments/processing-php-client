<?php
namespace Payline\Hal\Exception;

/**
 * Raised when trying to find a Link by its relation type
 * (rel) with the {@link Resource#getLinks(Rel)}
 * method but the value is a unique Link.
 */
class LinkUniqueException extends \Exception { }
