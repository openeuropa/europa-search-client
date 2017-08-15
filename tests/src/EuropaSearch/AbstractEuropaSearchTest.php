<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\AbstractEuropaSearchTest.
 */

namespace EC\EuropaWS\Tests;

use EC\EuropaSearch\Tests\EuropaSearchDummy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class AbstractEuropaSearchTest.
 *
 * Centralize all methods shared between test cases.
 *
 * @package EC\EuropaWS\Tests
 */
abstract class AbstractEuropaSearchTest extends TestCase
{
    /**
     * Gets the ContainerBuilder used by unit tests.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     *   The ContainerBuilder implementation
     */
    protected function getContainer()
    {
        return (new EuropaSearchDummy())->getClientContainer();
    }

    /**
     * Gets the default validator
     */
    protected function getDefaultValidator()
    {
        return $this->getContainer()->get('validator.default')->getValidator();
    }

    /**
     * Gets the validation error messages
     *
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $violations
     *    Object containing the validation error messages
     *
     * @return array
     *    The list of validation error messages;
     */
    protected function getViolations(ConstraintViolationListInterface $violations)
    {
        $collection = [];
        foreach ($violations as $violation) {
            $collection[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $collection;
    }
}
