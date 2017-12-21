<?php

namespace EC\EuropaSearch\Messages;

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
 * @package EC\EuropaSearch\Messages
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
