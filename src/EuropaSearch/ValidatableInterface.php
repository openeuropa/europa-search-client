<?php

namespace EC\EuropaSearch;

use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Interface ValidatableInterface.
 *
 * Implementing this interface allows an object to be validated by library mechanism.
 *
 * @package EC\EuropaSearch
 */
interface ValidatableInterface
{

    /**
     * Gets definition of the validation constraints existing on the object.
     *
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata);
}
