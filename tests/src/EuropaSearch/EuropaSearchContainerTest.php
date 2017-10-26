<?php

namespace EC\EuropaSearch\Tests;

use EC\EuropaSearch\EuropaSearch;
use EC\EuropaSearch\Tests\Helpers\Logger\TestLogger;
use Psr\Log\LogLevel;

/**
 * Class EuropaSearchContainerTest
 *
 * @package EC\EuropaSearch\Tests
 */
class EuropaSearchContainerTest extends AbstractEuropaSearchTest
{
    /**
     * Test that the system logger is used correctly by the services container.
     */
    public function testLoggerInjection()
    {
        $testedLogger = new TestLogger(LogLevel::INFO, ['dummy_setting' => 'Test setting does not change.']);
        $clientConfiguration = [
            'indexing_settings' => $this->getTestedIndexingServiceParams(),
            'search_settings' => $this->getTestedSearchServiceParams(),
            'services_settings' => [
                'logger' => $testedLogger,
                'log_level' => LogLevel::INFO,
            ],
        ];

        $europaSearch = new EuropaSearch($clientConfiguration);

        $europaSearch->getServiceById('europasearch.loggerManager')->logDebug('Message 1 debug', ['param_0' => 'param_0_value']);
        $europaSearch->getServiceById('europasearch.loggerManager')->logInfo('Message 2 info', ['param_1' => 'param_1_value']);
        $europaSearch->getServiceById('europasearch.loggerManager')->logInfo('Message 3 info', ['param_2' => 'param_2_value']);
        $europaSearch->getServiceById('europasearch.loggerManager')->logError('Message 4 error', ['param_3' => 'param_3_value']);
        $europaSearch->getServiceById('europasearch.loggerManager')->logCritical('Message 5 critical', ['param_4' => 'param_4_value']);

        $recordedLogs = $testedLogger->getLogs();

        $this->assertCount(0, $recordedLogs[LogLevel::DEBUG], 'The logger recorded unexpectedly the DEBUG messages.');
        $this->assertCount(2, $recordedLogs[LogLevel::INFO], 'The logger did not record correctly the INFO messages.');
        $this->assertCount(1, $recordedLogs[LogLevel::ERROR], 'The logger did not record correctly the ERROR messages.');
        $this->assertCount(1, $recordedLogs[LogLevel::CRITICAL], 'The logger did not record correctly the CRITICAL messages.');
        $this->assertArrayHasKey('Message 4 error', $recordedLogs[LogLevel::ERROR], 'The logger di not record correctly the ERROR message');
    }
}
