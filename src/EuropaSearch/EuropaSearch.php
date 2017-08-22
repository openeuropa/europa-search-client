<?php

/**
 * @file
 * Contains EC\EuropaSearch\EuropaSearch.
 */

namespace EC\EuropaSearch;

use EC\EuropaWS\ClientContainerFactory;

/**
 * Class EuropaSearch
 *
 * It is the client container factory of Europa Search.
 *
 * @package EC\EuropaSearch
 */
class EuropaSearch extends ClientContainerFactory
{

    /**
     * EuropaSearch constructor.
     */
    public function __construct()
    {
        $this->configRepoPath = __DIR__.'/config';
    }

    /**
     * Set the web service configuration if not done via the service.yml.
     *
     * @param EuropaSearchConfig $config
     *   The web service configuration.
     */
    public function setWSConfig(EuropaSearchConfig $config)
    {
        $this->buildClientContainer();
        $this->container->set('europaSearch.ws.settings', $config);
    }

    /**
     * Gets the indexing client dedicated to web contents.
     *
     * @return \EC\EuropaSearch\Clients\IndexingClient
     *   The indexing client.
     *
     * @throws ClientInstantiationException
     *   Raised if a problem occurred while retrieving the client.
     */
    public function getIndexingWebContentClient()
    {
        return $this->getClient('client.indexing.webDocument');
    }

    /**
     * Gets the searching client.
     *
     * @return \EC\EuropaSearch\Clients\IndexingClient
     *   The indexing client.
     *
     * @throws ClientInstantiationException
     *   Raised if a problem occurred while retrieving the client.
     */
    public function getSearchingClient()
    {
        return $this->getClient('client.searching.search');
    }
}
