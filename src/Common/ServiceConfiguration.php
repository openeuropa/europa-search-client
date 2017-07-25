<?php
/**
 * @file
 * Contains EC\EuropaSearch\ServiceConfiguration.
 */

namespace EC\EuropaSearch\Common;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ServiceConfiguration
 *
 * It represents the Europa Search services parameters.
 *
 * @package EC\EuropaSearch
 */
class ServiceConfiguration
{
    const MOCK_SERVICE_MODE = 0;
    const HTTP_SERVICE_MODE = 1;

    private $apiKey;

    private $database;

    private $serviceRoot;

    private $serviceMode = self::HTTP_SERVICE_MODE;

    /**
     * Gets the service mode enabled with the client.
     *
     * @return int
     *   The service mode:
     *   - MOCK_SERVICE_MODE: The mock mode connected to a mock for tests.
     *   - HTTP_SERVICE_MODE: The service mode connected to the real services.
     */
    public function getServiceMode()
    {
        return $this->serviceMode;
    }

    /**
     * Determines if the client is configured to use a mock or not.
     *
     * @return bool
     *   true if the service is a mock; otherwise it is a false.
     */
    public function isMockService()
    {
        return ($this->serviceMode == self::MOCK_SERVICE_MODE);
    }

    /**
     * Sets the service mode enabled with the client.
     *
     * @param int $serviceMode
     *   The service mode:
     *   - MOCK_SERVICE_MODE: The mock mode connected to a mock for tests.
     *   - HTTP_SERVICE_MODE: The service mode connected to the real services.
     */
    public function setServiceMode($serviceMode)
    {
        $this->serviceMode = $serviceMode;
    }

    /**
     * Gets the service APIKey to specify to the Europa Search service.
     *
     * @return string
     *   The service's APIKey.
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets the service APIKey to specify to the Europa Search service.
     *
     * @param string $apiKey
     *   The service's APIKey.
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Gets the database to specify to the Europa Search service.
     *
     * @return string
     *    The database to specify.
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Sets the database to specify to the Europa Search service.
     *
     * @param string $database
     *    The database to specify.
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * Gets the url root used to connect to the Europa Search service.
     *
     * @return string
     *    the url to use.
     */
    public function getServiceRoot()
    {
        return $this->serviceRoot;
    }

    /**
     * Sets the url root used to connect to the Europa Search service.
     *
     * The root can contain the port definition, e.g.
     * "www.europa_search.com:8080/"
     *
     * @param string $serviceRoot .
     *    the url to use.
     */
    public function setServiceRoot($serviceRoot)
    {
        $this->serviceRoot = $serviceRoot;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('serviceRoot', [
            new Assert\NotBlank(),
            new Assert\Url(),
        ]);
        $metadata->addPropertyConstraints('apiKey', [
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ]);
        $metadata->addPropertyConstraint('serviceMode', new Assert\Choice(
            array(self::HTTP_SERVICE_MODE, self::MOCK_SERVICE_MODE)
        ));
        $metadata->addPropertyConstraint('database', new Assert\Type('string'));
    }
}
