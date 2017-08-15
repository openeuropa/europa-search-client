<?php

/**
 * @file
 * Contains EC\EuropaWS\Exceptions\ValidationException.
 */

namespace EC\EuropaWS\Exceptions;

use \Exception;

/**
 * Class ValidationException.
 *
 * This type of exceptions is catch when a message or the web service
 * configuration is not valid.
 *
 * @package EC\EuropaWS\Exceptions
 */
class ValidationException extends Exception
{
    /**
     * List of the validation error messages.
     *
     * @var array
     */
    private $validationErrors;

    /**
     * Gets the list of validation errors
     *
     * @return array
     *   Array of string containing error messages.
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * Sets the list of validation errors
     *
     * @param array $validationErrors
     *   Array of string containing error messages.
     */
    public function setValidationErrors(array $validationErrors)
    {
        $this->validationErrors = $validationErrors;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        $message = $this->getMessage().PHP_EOL;
        foreach ($this->validationErrors as $error) {
            $message .= $error.PHP_EOL;
        }

        return $message;
    }
}
