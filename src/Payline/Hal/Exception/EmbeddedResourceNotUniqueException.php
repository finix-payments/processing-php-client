<?php
namespace Payline\Hal\Exception;

/**
 * Raised when trying to find an embedded Resource by its relation type
 * (rel) with the {@link Resource#getEmbeddedResource(Rel)}
 * method but the value is an array of embedded Resources.
 */
class EmbeddedResourceNotUniqueException extends \Exception { }
