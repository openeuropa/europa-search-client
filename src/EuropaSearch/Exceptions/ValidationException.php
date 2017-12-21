<?php

namespace EC\EuropaSearch\Exceptions;

use \Exception;

/**
 * Class ValidationException.
 *
 * This type of exceptions is catch when a message or the web service
 * configuration is not valid.
 * The exception returns 282 as code.
 *
 * @package EC\EuropaSearch\Exceptions
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
     *   [optional] The Exception message to throw.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = "", Exception $previous = null)
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
