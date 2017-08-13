<?php

/**
 * @file
 * Contains EC\EuropaWS\ValidatableInterface.
 */

namespace EC\EuropaWS;

use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Interface ValidatableInterface.
 *
 * Implementing this interface allows an object to be validated by library mechanism.
 *
 * @package EC\EuropaWS
 */
interface ValidatableInterface
{

    /**
     * Gets Ddefinition of the validation constraints existing on the object.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata);
}
