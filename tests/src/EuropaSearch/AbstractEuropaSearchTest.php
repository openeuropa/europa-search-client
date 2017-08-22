<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\AbstractEuropaSearchTest.
 */

namespace EC\EuropaSearch\Tests;

use EC\EuropaSearch\EuropaSearch;
use EC\EuropaSearch\EuropaSearchConfig;
use EC\EuropaSearch\Tests\EuropaSearchDummy;
use EC\EuropaWS\Tests\Dummies\WSConfigurationDummy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class AbstractEuropaSearchTest.
 *
 * Centralizes all methods shared between test cases.
 *
 * @package EC\EuropaWS\Tests
 */
abstract class AbstractEuropaSearchTest extends TestCase
{

    /**
     * Gets the client factory for tests.
     *
     * @return EuropaSearch
     *   The client factory.
     */
    protected function getFactory()
    {

        $container = new EuropaSearch();

        $wsSettings = [
            'URL' => 'http://www.dummy.com/ws',
            'APIKey' => 'abcd1234',
            'database' => 'abcd',
        ];
        $config = new EuropaSearchConfig($wsSettings, 'dumb', 'dumber');
        $config->setUserName('dumb');
        $config->setUserPassword('dumber');
        $container->setWSConfig($config);

        return $container;
    }

    /**
     * Gets the ContainerBuilder used by unit tests.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     *   The ContainerBuilder implementation
     */
    protected function getContainer()
    {

        $factory = $this->getFactory();

        return $factory->getClientContainer();
    }

    /**
     * Gets the default validator
     */
    protected function getDefaultValidator()
    {
        return $this->getFactory()->getDefaultValidator();
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
