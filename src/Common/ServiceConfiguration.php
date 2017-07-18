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

    private $apiKey;

    private $database;

    private $serviceRoot;

    /**
     * Gets the service APIKeyto specify to the Europa Search service.
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
        $metadata->addPropertyConstraint('serviceRoot', new Assert\NotBlank());
        $metadata->addPropertyConstraint('database', new Assert\Type('string'));
        $metadata->addPropertyConstraint('apiKey', new Assert\NotBlank());
        $metadata->addPropertyConstraint('apiKey', new Assert\Type('string'));
    }
}
