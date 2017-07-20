<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\AbstractTest.
 */

namespace EC\EuropaSearch\Tests;

use EC\EuropaSearch\Common\ServiceConfiguration;
use \PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class AbstractTest.
 *
 * @package EC\Poetry\Tests
 */
abstract class AbstractTest extends TestCase
{
    /**
     * Gets the service configuration to work with a HTTP mock.
     *
     * @return ServiceConfiguration $serviceConfiguration
     *    The service configuration to work with a HTTP mock.
     */
    protected function getServiceConfigurationMock()
    {
        // TODO. Implement the method when mock is ready.
    }

    /**
     * Gets the service configuration for internal process tests.
     *
     * @return ServiceConfiguration $serviceConfiguration
     *    The service configuration
     */
    protected function getServiceConfigurationDummy()
    {
        $serviceConfiguration = new ServiceConfiguration();
        $serviceConfiguration->setApiKey('1234567890');
        $serviceConfiguration->setDatabase('dummy');
        $serviceConfiguration->setServiceRoot('http://service.srv/');

        return $serviceConfiguration;
    }

    /**
     * Geets the validation error messages
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
