<?php
namespace Finix\Hal\Exception;

/**
 * Raised when trying to find a Link by its relation type
 * (rel) with the {@link Resource#getLink(Rel)}
 * method but the value is an array of Links.
 */
class LinkNotUniqueException extends \Exception { }
