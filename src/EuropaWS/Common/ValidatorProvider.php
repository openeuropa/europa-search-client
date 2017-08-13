<?php

/**
 * @file
 * Contains EC\EuropaWS\Common\ValidatorProvider.
 */

namespace EC\EuropaWS\Common;

use Symfony\Component\Validator\ValidatorBuilder;

/**
 * Class ValidatorProvider.
 *
 * It provides a custom
 * Symfony\Component\Validator\Validator\RecursiveValidator.
 * This one retrieve constraints to validate from the "getConstraints()" method
 * declared in the object to validate.
 *
 * @package EC\EuropaWS\Common
 */
class ValidatorProvider
{

    private $validator;

    /**
     * ValidatorProvider constructor.
     */
    public function __construct()
    {
        $validatorBuilder = new ValidatorBuilder();
        $validatorBuilder->addMethodMapping('getConstraints');
        $this->validator = $validatorBuilder->getValidator();

        // @todo Implementation for additional validators.
    }

    /**
     * Gets the custom validator.
     *
     * @return Symfony\Component\Validator\Validator\RecursiveValidator
     *   The validator.
     */
    public function getValidator()
    {
        return $this->validator;
    }
}
