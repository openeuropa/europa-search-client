<?php

/**
 * @file
 * Contains EC\EuropaWS\Common\DefaultValidator.
 */

namespace EC\EuropaWS\Common;

use Symfony\Component\Validator\ValidatorBuilder;

/**
 * Class ValidatorProvider.
 *
 * It provides a custom
 * Symfony\Component\Validator\ValidatorBuilder.
 * This one allows instantiating a validator that retrieve constraints to
 * validate from the "getConstraints()" method declared in the object to
 * validate.
 *
 * @package EC\EuropaWS\Common
 */
class DefaultValidatorBuilder extends ValidatorBuilder
{

    /**
     * ValidatorProvider constructor.
     */
    public function __construct()
    {
        $this->addMethodMapping('getConstraints');
    }
}