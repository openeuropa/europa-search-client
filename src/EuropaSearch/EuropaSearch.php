<?php

/**
 * @file
 * Contains EC\EuropaSearch\EuropaSearch.
 */

namespace EC\EuropaSearch;

use EC\EuropaWS\ClientContainerFactory;
use EC\EuropaWS\Common\WSConfigurationInterface;

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
     *
     * @param WSConfigurationInterface $configuration
     *   The client configuration.
     */
    public function __construct(WSConfigurationInterface $configuration)
    {
        $this->configRepoPath = __DIR__.'/config';
        $this->configuration = $configuration;
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
