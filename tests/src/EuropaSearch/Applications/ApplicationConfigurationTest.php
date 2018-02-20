<?php

namespace src\EuropaSearch\Applications;

use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class ApplicationConfigurationTest.
 *
 * It tests that the different Application objects receive the
 * corresponding settings.
 *
 * @package src\EuropaSearch\Applications
 */
class ApplicationConfigurationTest extends AbstractEuropaSearchTest
{


    /**
     * Test that the Indexing application uses the right configuration.
     *
     * This configuration comes from the container.
     */
    public function testIndexingApplicationConfiguration()
    {
        $factory = $this->getFactory([]);
        $application = $factory->getIndexingApplication();
        $applicationConfig = $application->getApplicationConfiguration();

        $this->assertArraySubset(
            $this->getTestedIndexingServiceParams(),
            $applicationConfig->getConnectionConfigurations(),
            'The returned indexing application does not have the expected configuration. '
        );
    }

    /**
     * Test that the Search application uses the right configuration.
     *
     * This configuration comes from the container.
     */
    public function testSearchApplicationConfiguration()
    {
        $factory = $this->getFactory([]);
        $application = $factory->getSearchApplication();
        $applicationConfig = $application->getApplicationConfiguration();

        $this->assertArraySubset(
            $this->getTestedSearchServiceParams(),
            $applicationConfig->getConnectionConfigurations(),
            'The returned search application does not have the expected configuration.'
        );
    }
}
