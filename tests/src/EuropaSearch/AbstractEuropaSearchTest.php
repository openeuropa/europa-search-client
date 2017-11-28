<?php

namespace EC\EuropaSearch\Tests;

use EC\EuropaSearch\EuropaSearch;
use EC\EuropaSearch\EuropaSearchConfig;
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
     * Gets a dummy service configuration for test purpose.
     *
     * @return array
     *   The dummy service configuration.
     */
    protected function getTestedIndexingServiceParams()
    {
        return [
            'url_root' => 'https://intragate.acceptance.ec.europa.eu/es/ingestion-api',
            'api_key' => 'a221108a-180d-HTTP-INDEXING-TEST',
            'database' => 'EC-EUROPA-DUMMY-INDEXING',
        ];
    }

    /**
     * Gets a dummy service configuration for test purpose.
     *
     * @return array
     *   The dummy service configuration.
     */
    protected function getTestedSearchServiceParams()
    {
        return [
            'url_root' => 'https://intragate.acceptance.ec.europa.eu/es/search-api',
            'api_key' => 'a221108a-180d-HTTP-SEARCH-TEST',
            'database' => 'EC-EUROPA-DUMMY-SEARCH',
        ];
    }

    /**
     * Gets a dummy indexing application configuration for test purpose.
     *
     * @param array $mockResponses
     *   [optional] Array of Response objects used by the mock called during
     *   the test.
     *
     * @return \EC\EuropaSearch\EuropaSearchConfig
     *   The dummy application configuration.
     */
    protected function getDummyIndexingAppConfig(array $mockResponses = [])
    {
        $config = new EuropaSearchConfig($this->getTestedIndexingServiceParams());
        $config->setUseMock(true);
        if ($mockResponses) {
            $config->setMockConfigurations($mockResponses);
        }

        return $config;
    }

    /**
     * Gets a dummy search application configuration for test purpose.
     *
     * @param array $mockResponses
     *   [optional] Array of Response objects used by the mock called during
     *   the test.
     *
     * @return \EC\EuropaSearch\EuropaSearchConfig
     *   The dummy application configuration.
     */
    protected function getDummySearchAppConfig(array $mockResponses = [])
    {
        $config = new EuropaSearchConfig($this->getTestedSearchServiceParams());
        $config->setUseMock(true);
        if ($mockResponses) {
            $config->setMockConfigurations($mockResponses);
        }

        return $config;
    }

    /**
     * Gets the client factory for tests.
     *
     * @param array $mockResponses
     *   [optional] Array of Response objects used by the mock called during
     *   the test.
     *
     * @return \EC\EuropaSearch\EuropaSearch
     *   The client factory.
     */
    protected function getFactory(array $mockResponses = [])
    {
        $container = new EuropaSearch();

        $config = $this->getDummyIndexingAppConfig($mockResponses);
        $container->updateIndexingClientConfiguration($config);
        $config = $this->getDummySearchAppConfig($mockResponses);
        $container->updateSearchClientConfiguration($config);

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
