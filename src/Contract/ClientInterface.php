<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\Facets;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\EuropaSearchClient\Model\Search;

interface ClientInterface
{
    /**
     * Executes a search.
     *
     * @param string|null $text
     * @param array|null $languages
     * @param array|null $query
     * @param $sortField
     *   This parameter can receive an array of arrays or a single field name. If an array is used it should have pairs of field and direction.
     * @param string|null $sortOrder
     *   This parameter needs to be null if sortField is an array.
     *   If $sortField is a string, the value of this parameter will be used as sorting order.
     * @param int|null $pageNumber
     * @param int|null $pageSize
     * @param string|null $highlightRegex
     * @param int|null $highlightLimit
     * @param string|null $sessionToken
     *
     * @return Search
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function search(
        ?string $text = null,
        ?array $languages = null,
        ?array $query = null,
        $sortField = null,
        ?string $sortOrder = null,
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
     * @param string|null $facetSort
     * @param string|null $sessionToken
     *
     * @return Facets
     */
    public function getFacets(
        ?string $text = null,
        ?array $languages = null,
        ?string $displayLanguage = null,
        ?array $query = null,
        ?string $facetSort = null,
        ?string $sessionToken = null
    ): Facets;

    /**
     * @param string $uri
     * @param string|null $text
     * @param array|null $languages
     * @param array|null $metadata
     * @param string|null $reference
     * @param array|null $aclUsers
     * @param array|null $aclNoUsers
     * @param array|null $aclGroups
     * @param array|null $aclNoGroups
     *
     * @return Ingestion
     */
    public function ingestText(
        string $uri,
        ?string $text = null,
        ?array $languages = null,
        ?array $metadata = null,
        ?string $reference = null,
        ?array $aclUsers = null,
        ?array $aclNoUsers = null,
        ?array $aclGroups = null,
        ?array $aclNoGroups = null
    ): Ingestion;

    /**
     * @param string $uri
     * @param string|null $file
     * @param array|null $languages
     * @param array|null $metadata
     * @param string|null $reference
     * @param array|null $aclUsers
     * @param array|null $aclNoUsers
     * @param array|null $aclGroups
     * @param array|null $aclNoGroups
     *
     * @return Ingestion
     */
    public function ingestFile(
        string $uri,
        ?string $file = null,
        ?array $languages = null,
        ?array $metadata = null,
        ?string $reference = null,
        ?array $aclUsers = null,
        ?array $aclNoUsers = null,
        ?array $aclGroups = null,
        ?array $aclNoGroups = null
    ): Ingestion;

    /**
     * @param string $reference
     * @return bool
     */
    public function deleteDocument(string $reference): bool;
}
