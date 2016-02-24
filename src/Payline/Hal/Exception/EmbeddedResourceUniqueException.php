<?php
namespace Payline\Hal\Exception;

/**
 * Raised when trying to find an embedded Resource by its relation type
 * (rel) with the {@link Resource#getEmbeddedResources(Rel)}
 * method but the value is a unique embedded Resource.
 */
class EmbeddedResourceUniqueException extends \Exception { }
