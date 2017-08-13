<?php

/**
 * @file
 * Contains EC\EuropaWS\Clients\ClientProvider.
 */

namespace EC\EuropaWS\Clients;

use EC\EuropaWS\ClientContainerFactory;
use EC\EuropaWS\Exceptions\ClientInstantiationException;

/**
 * Class ClientProvider.
 *
 * It supplies the right client class for send a message to a specific
 * web service.
 *
 * @package EC\EuropaWS\Clients
 */
class ClientProvider
{

    /**
     * The client container to use in the different method.
     *
     * @var \EC\EuropaWS\ClientContainer
     */
    private $factory;

    /**
     * Prefix for identifier of service used as client.
     */
    const CLIENT_ID_PREFIX = 'client.';

    /**
     * ClientProvider constructor.
     *
     * @param ClientContainerFactory $factory
     *   (Optional) The client container containing the client services
     *   configuration.
     */
    public function __construct(ClientContainerFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Gets the client object.
     *
     * @param string $clientId
     *   The client identifier as defined in the container definition.
     *
     * @return ClientInterface
     *   The retrieved client.
     *
     * @throws ClientInstantiationException
     *   Raised if the client is not retrieved
     */
    public function getClient($clientId)
    {
        try {
            return $this->factory->getClientContainer()->get($clientId);
        } catch (\Exception $e) {
            throw new ClientInstantiationException('The client has not been retrieved', $e);
        }
    }
}
