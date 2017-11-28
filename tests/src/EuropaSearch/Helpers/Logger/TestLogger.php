<?php

namespace EC\EuropaSearch\Tests\Helpers\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Class TestLogger.
 *
 * @package EC\EuropaSearch\Tests\Helpers
 */
class TestLogger extends AbstractLogger
{
    /**
     * Detailed debug information.
     */
    const DEBUG = 100;

    /**
     * Interesting events.
     *
     * Examples: User logs in, SQL logs.
     */
    const INFO = 200;

    /**
     * Uncommon events.
     */
    const NOTICE = 250;

    /**
     * Exceptional occurrences that are not errors.
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    const WARNING = 300;

    /**
     * Runtime errors.
     */
    const ERROR = 400;

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     */
    const CRITICAL = 500;

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    const ALERT = 550;

    /**
     * Urgent alert.
     */
    const EMERGENCY = 600;

    /**
     * Map of PSR3 log levels on the implementation's levels.
     */
    const LOG_LEVEL_MAPPING = [
        LogLevel::EMERGENCY => self::EMERGENCY,
        LogLevel::ALERT => self::ALERT,
        LogLevel::CRITICAL => self::CRITICAL,
        LogLevel::ERROR => self::ERROR,
        LogLevel::WARNING => self::WARNING,
        LogLevel::NOTICE => self::NOTICE,
        LogLevel::INFO => self::INFO,
        LogLevel::DEBUG => self::DEBUG,
    ];

    /**
     * Random configuration for test only.
     *
     * @var array
     */
    private $configuration = [];

    /**
     * Random configuration for test only.
     *
     * @var array
     */
    private $logLevel = LogLevel::INFO;

    /**
     * Test log storage.
     *
     * @var array
     */
    protected $logs = [
        LogLevel::EMERGENCY => [],
        LogLevel::ALERT     => [],
        LogLevel::CRITICAL  => [],
        LogLevel::ERROR     => [],
        LogLevel::WARNING   => [],
        LogLevel::NOTICE    => [],
        LogLevel::INFO      => [],
        LogLevel::DEBUG     => [],
    ];

    /**
     * TestLogger constructor.
     *
     * @param string $logLevel
     *   The PSR3 log level the logger must used
     * @param array  $configuration
     *   Test configuration array.
     */
    public function __construct($logLevel, array $configuration = [])
    {
        $this->configuration = $configuration;
        $this->logLevel = self::LOG_LEVEL_MAPPING[$logLevel];
    }

    /**
     * Gets the logger configuration.
     *
     * @return array
     *  The logger configuration.
     */
    public function getLogConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = [])
    {
        $messageLogLevel = self::LOG_LEVEL_MAPPING[$level];
        if ($messageLogLevel >= $this->logLevel) {
            $this->logs[$level][$message] = $context;
        }
    }

    /**
     * Get logs.
     *
     * @return array
     *   Property value.
     */
    public function getLogs()
    {
        return $this->logs;
    }
}
