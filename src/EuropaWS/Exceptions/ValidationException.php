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
 * Its code is 282.
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
     * ValidationException constructor.
     *
     * @param string    $message
     *   The exception message.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining.
     */
    public function __construct($message, Exception $previous)
    {
        parent::__construct($message, 282, $previous);
    }

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
