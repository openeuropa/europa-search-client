<?php
/**
 * Created by PhpStorm.
 * User: gillesdeudon
 * Date: 20/07/17
 * Time: 11:45
 */

namespace EC\EuropaSearch\Common\Exceptions;

/**
 * Class AbstractException.
 *
 * It enriches the Exception class by supplying to detail the list of runtime
 * violations that cause the exception.
 *
 * @package EC\EuropaSearch\Common\Exceptions
 */
abstract class AbstractException extends \Exception
{
    /**
     * List of configuration violations.
     *
     * @var array
     */
    private $errorList;

    /**
     * @return array
     */
    public function getErrorList()
    {
        return $this->errorList;
    }

    /**
     * Sets list of detected errors to attach to the exception.
     *
     * @param array $errorList
     *    The error list
     */
    public function setErrorList($errorList)
    {
        $this->errorList = $errorList;

        $fullMessage = $this->message.PHP_EOL;
        $fullMessage .= 'Detected errors:'.PHP_EOL;
        foreach ($errorList as $error) {
            $fullMessage .= $error;
        }
        $this->message = $fullMessage;
    }
}
