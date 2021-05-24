<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use League\Container\ContainerAwareInterface;
use OpenEuropa\EuropaSearchClient\Model\Facet;
use OpenEuropa\EuropaSearchClient\Model\Facets;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\EuropaSearchClient\Model\Search;

interface ClientInterface extends ContainerAwareInterface
{
    /**
     * Executes a search.
     *
     * @param string|null $text
     * @param array|null $languages
     * @param array|null $query
     * @param array|null $sort
     * @param int|null $pageNumber
     * @param int|null $pageSize
     * @param string|null $highlightRegex
     * @param int|null $highlightLimit
     * @param string|null $sessionToken
     *
     * @return Search
     */
    public function search(
        ?string $text = null,
        ?array $languages = null,
        ?array $query = null,
        ?array $sort = null,
        ?int $pageNumber = null,
        ?int $pageSize = null,
        ?string $highlightRegex = null,
        ?int $highlightLimit = null,
        ?string $sessionToken = null
    ): Search;

    /**
     * Executes a facet search.
     *
     * @param string|null $text
     * @param array|null $languages
     * @param string|null $displayLanguage
     * @param array|null $query
     * @param array|null $sort
     * @param string|null $sessionToken
     *
     * @return Facet
     */
    public function getFacets(
        ?string $text = null,
        ?array $languages = null,
        ?string $displayLanguage = null,
        ?array $query = null,
        ?array $sort = null,
        ?string $sessionToken = null
    ): Facets;

    /**
     * @param string $uri
     * @param string|null $text
     * @param array|null $languages
     * @param array|null $metadata
     * @param string|null $reference
     *
     * @return Ingestion
     */
    public function ingestText(
        string $uri,
        ?string $text,
        ?array $languages = null,
        ?array $metadata = null,
        ?string $reference = null
    ): Ingestion;

    /**
     * @param string $reference
     * @return bool
     */
    public function deleteDocument(string $reference): bool;
}
