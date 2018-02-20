<?php

namespace OpenEuropa\EuropaSearch\Services;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class LogsManager.
 *
 * It encapsulates the PSR3 logger in order to add to him some
 * additional features whatever its implementation.
 *
 * @package OpenEuropa\EuropaSearch\Services
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class LogsManager
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
     * The LoggerInterface implementation use to record logs.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * The implementation's log level define for the object instance.
     *
     * @var string
     */
    protected $logLevel;

    /**
     * LoggerManager constructor.
     *
     * @param LoggerInterface $logger
     *   The logger to use.
     * @param String          $logLevel
     *   The minimum level of log to generate.
     */
    public function __construct(LoggerInterface $logger = null, $logLevel = LogLevel::ERROR)
    {
        $this->logger = $logger;
        $this->logLevel = self::LOG_LEVEL_MAPPING[$logLevel];
    }

    /**
     * Defines the LoggerInterface implementation to use with the instance.
     *
     * @param LoggerInterface $logger
     *   The logger to use.
     * @param String          $logLevel
     *   The minimum level of log to generate.
     */
    public function defineLogger(LoggerInterface $logger, $logLevel)
    {
        $this->logger = $logger;
        $this->logLevel = self::LOG_LEVEL_MAPPING[$logLevel];
    }

    /**
     * Determines if the current log level is "debug" or higher.
     *
     * @return boolean
     *   TRUE if debug message and higher must be created.
     */
    public function isDebug()
    {
        return ($this->logLevel >= self::DEBUG);
    }

    /**
     * Determines if the current log level is "info" or higher.
     *
     * @return boolean
     *   TRUE if debug message and higher must be created.
     */
    public function isInfo()
    {
        return ($this->logLevel >= self::INFO);
    }

    /**
     * Determines if the current log level is "notice" or higher.
     *
     * @return boolean
     *   TRUE if debug message and higher must be created.
     */
    public function isNotice()
    {
        return ($this->logLevel >= self::NOTICE);
    }

    /**
     * Determines if the current log level is "warning" or higher.
     *
     * @return boolean
     *   TRUE if debug message and higher must be created.
     */
    public function isWarning()
    {
        return ($this->logLevel >= self::WARNING);
    }

    /**
     * Determines if the current log level is "error" or higher.
     *
     * @return boolean
     *   TRUE if debug message and higher must be created.
     */
    public function isError()
    {
        return ($this->logLevel >= self::ERROR);
    }

    /**
     * Determines if the current log level is "critical" or higher.
     *
     * @return boolean
     *   TRUE if debug message and higher must be created.
     */
    public function isCritical()
    {
        return ($this->logLevel >= self::CRITICAL);
    }

    /**
     * Determines if the current log level is "alert" or higher.
     *
     * @return boolean
     *   TRUE if debug message and higher must be created.
     */
    public function isAlert()
    {
        return ($this->logLevel >= self::ALERT);
    }

    /**
     * Determines if the current log level is "emergency" or higher.
     *
     * @return boolean
     *   TRUE if debug message and higher must be created.
     */
    public function isEmergency()
    {
        return ($this->logLevel >= self::EMERGENCY);
    }

    /**
     * Adds a log record at the DEBUG level.
     *
     * @param string $message
     *   The log message.
     * @param array  $context
     *   The log context.
     */
    public function logDebug($message, array $context = array())
    {
        $this->logger->debug($message, $context);
    }

    /**
     * Adds a log record at the INFO level.
     *
     * @param string $message
     *   The log message.
     * @param array  $context
     *   The log context.
     */
    public function logInfo($message, array $context = array())
    {
        $this->logger->info($message, $context);
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * @param string $message
     *   The log message.
     * @param array  $context
     *   The log context.
     */
    public function logNotice($message, array $context = array())
    {
        $this->logger->notice($message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * @param string $message
     *   The log message.
     * @param array  $context
     *   The log context.
     */
    public function logWarning($message, array $context = array())
    {
        $this->logger->warning($message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * @param string $message
     *   The log message.
     * @param array  $context
     *   The log context.
     */
    public function logError($message, array $context = array())
    {
        $this->logger->error($message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * @param string $message
     *   The log message.
     * @param array  $context
     *   The log context.
     */
    public function logCritical($message, array $context = array())
    {
        $this->logger->critical($message, $context);
    }

    /**
     * Adds a log record at the ALERT level.
     *
     * @param string $message
     *   The log message.
     * @param array  $context
     *   The log context.
     */
    public function logAlert($message, array $context = array())
    {
        $this->logger->alert($message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * @param string $message
     *   The log message.
     * @param array  $context
     *   The log context.
     */
    public function logEmergency($message, array $context = array())
    {
        $this->logger->emergency($message, $context);
    }
}
