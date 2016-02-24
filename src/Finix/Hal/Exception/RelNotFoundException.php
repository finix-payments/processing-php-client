<?php
namespace Payline\Hal\Exception;
use Payline\Hal\CustomRel;
use Payline\Hal\RegisteredRel;

/**
 * Raised when trying to get a link or an embedded
 * resource by a non-existing relation type (Rel).
 */
class RelNotFoundException extends \Exception {
    private $missingRel;
    private $availableRelations;

    /**
     * @param RegisteredRel|CustomRel $missingRel The missing relation type.
     * @param array|String $availableRelations The list of available relation types.
     */
    public function __construct($missingRel, array $availableRelations) {
        parent::__construct(
            'Rel not found: ' . $missingRel . '. ' .
            'Relation types available: ' . implode(', ', $availableRelations) . '.'
        );

        $this->missingRel = $missingRel;
        $this->availableRelations = $availableRelations;
    }

    /**
     * @return  RegisteredRel|CustomRel     The missing relation type.
     */
    public function getMissingRel() {
        return $this->missingRel;
    }

    /**
     * Returns the list of available relation types available in the
     * _links or _embedded property the given Rel was missing in.
     * @return array.
     */
    public function getAvailableRelations() {
        return $this->availableRelations;
    }
}
