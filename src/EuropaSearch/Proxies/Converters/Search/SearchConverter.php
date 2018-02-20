<?php

namespace OpenEuropa\EuropaSearch\Proxies\Converters\Search;

use OpenEuropa\EuropaSearch\EuropaSearchConfig;
use OpenEuropa\EuropaSearch\Messages\Search\SearchMessage;
use OpenEuropa\EuropaSearch\Transporters\Requests\Search\SearchRequest;
use OpenEuropa\EuropaSearch\Messages\Search\SearchResponse;
use OpenEuropa\EuropaSearch\Messages\Search\SearchResult;
use OpenEuropa\EuropaSearch\Proxies\Converters\AbstractMessageConverter;
use OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface;

/**
 * Class SearchConverter.
 *
 * Converter for SearchMessage object.
 *
 * @package OpenEuropa\EuropaSearch\Proxies\Converters\Search
 */
class SearchConverter extends AbstractMessageConverter
{
    /**
     * {@inheritDoc}
     */
    public function convertMessage(ValidatableMessageInterface $message, EuropaSearchConfig $configuration)
    {
        $request = new SearchRequest();

        $conversionMapping = [
          'getSearchedLanguages' => 'setLanguages',
          'getHighLightLimit' => 'setHighlightLimit',
          'getHighlightRegex' => 'setHighlightRegex',
          'getPaginationLocation' => 'setPageNumber',
          'getPaginationSize' => 'setPageSize',
          'getSessionToken' => 'setSessionToken',
          'getSearchedText' => 'setText',
        ];

        foreach ($conversionMapping as $getMethod => $setMethod) {
            $parameter = $message->$getMethod();
            if (!empty($parameter)) {
                $request->$setMethod($parameter);
            }
        }

        // Data retrieved from the web services configuration.
        $WSSettings = $configuration->getConnectionConfigurations();
        $request->setAPIKey($WSSettings['api_key']);

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent, EuropaSearchConfig $configuration)
    {
        $request = $this->convertMessage($message, $configuration);

        // Build the final sort value to send to the service.
        if (!empty($convertedComponent['sort_metadata'])) {
            $sort = array_keys($convertedComponent['sort_metadata']);
            if (!empty($sort)) {
                $sort = reset($sort);
                $sortDirection = ($message->getSortDirection()) ?: SearchMessage::SEARCH_SORT_ASC;
                $sort .= ':'.$sortDirection;
                $request->setSort($sort);
            }
        }

        if (!empty($convertedComponent['search_query'])) {
            $queryComponents = $convertedComponent['search_query'];
            $request->addConvertedComponents($queryComponents);
        }

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageResponse($response, EuropaSearchConfig $configuration)
    {
        $rawResult = parent::convertMessageResponse($response, $configuration);

        $searchResponse = new SearchResponse();

        foreach ($rawResult as $attributeName => $attributeValue) {
            $this->setSearchResponseAttribute($searchResponse, $attributeName, $attributeValue);
        }

        return $searchResponse;
    }

    /**
     * Sets a specific attribute of a SearchResponse object.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Search\SearchResponse $convertedResponse
     *   The SearchResponse object to populate.
     * @param string         $attributeName
     *   The name of the attribute to populate.
     * @param mixed          $attributeValue
     *   The value of the attribute to populate.
     */
    private function setSearchResponseAttribute(SearchResponse $convertedResponse, $attributeName, $attributeValue)
    {
        if ('queryLanguage' == $attributeName) {
            $this->setSearchResponseLanguageData($convertedResponse, $attributeValue);

            return;
        }

        if ('results' == $attributeName) {
            $this->setSearchResponseResults($convertedResponse, $attributeValue);

            return;
        }

        $mappingKeys = [
            'terms' => 'setSearchedTerms',
            'totalResults' => 'setTotalResults',
            'pageNumber' => 'setPageNumber',
            'pageSize' => 'setPageSize',
            'sort' => 'setResultSorting',
        ];

        if (isset($mappingKeys[$attributeName])) {
            $function = $mappingKeys[$attributeName];
            $convertedResponse->{$function}($attributeValue);
        }
    }

    /**
     * Sets the language and its probability of a SearchResponse object.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Search\SearchResponse $convertedResponse
     *   The object for which language data are to be set.
     * @param \stdClass      $queryLanguage
     *   The raw object containing values to set.
     */
    private function setSearchResponseLanguageData(SearchResponse $convertedResponse, \stdClass $queryLanguage)
    {
        if (isset($queryLanguage->language)) {
            $convertedResponse->setLanguage($queryLanguage->language);
        }

        if (isset($queryLanguage->probability)) {
            $convertedResponse->setLanguageProbability($queryLanguage->probability);
        }
    }

    /**
     * Sets the results list of a SearchResponse object.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Search\SearchResponse $convertedResponse
     *   The object where results are to be set.
     * @param array          $rawResults
     *   Array containing the raw results as received from the service.
     */
    private function setSearchResponseResults(SearchResponse $convertedResponse, array $rawResults)
    {
        foreach ($rawResults as $result) {
            $convertedResult = $this->convertSearchResult($result);
            $convertedResponse->addResult($convertedResult);
        }
    }

    /**
     * Converts a raw search result received from the service.
     *
     * @param \stdClass $searchResult
     *   The raw result object to convert.
     *
     * @return \OpenEuropa\EuropaSearch\Messages\Search\SearchResult
     *   The converted object.
     */
    private function convertSearchResult(\stdClass $searchResult)
    {
        $result = new SearchResult();
        foreach ($searchResult as $attributeName => $attributeValue) {
            $this->setSearchResultAttribute($result, $attributeName, $attributeValue);
        }

        return $result;
    }

    /**
     * Sets a specific attribute of a SearchResult object.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Search\SearchResult $convertedResult
     *   The SearchResult object to populate.
     * @param string       $attributeName
     *   The name of the attribute to populate.
     * @param mixed        $attributeValue
     *   The value of the attribute to populate.
     */
    private function setSearchResultAttribute(SearchResult $convertedResult, $attributeName, $attributeValue)
    {
        if ('metadata' == $attributeName) {
            $this->setSearchResultMetadata($convertedResult, $attributeValue);

            return;
        }

        $mappingKeys = [
            'reference' => 'setResultReference',
            'url' => 'setEuropaSearchURL',
            'contentType' => 'setContentType',
            'databaseLabel' => 'setDatabaseLabel',
            'database' => 'setDatabase',
            'summary' => 'setResultSummary',
            'weight' => 'setSortingWeight',
            'content' => 'setResultFullContent',
            'accessRestriction' => 'setIsAccessRestricted',
        ];

        if (isset($mappingKeys[$attributeName])) {
            $function = $mappingKeys[$attributeName];
            $convertedResult->{$function}($attributeValue);
        }
    }

    /**
     * Sets the metadata of a SearchResult object.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Search\SearchResult $convertedResult
     *   The SearchResult object to populate.
     * @param \stdClass    $metadataList
     *   Raw object containing the metadata list.
     */
    private function setSearchResultMetadata(SearchResult $convertedResult, \stdClass $metadataList)
    {
        foreach ($metadataList as $name => $value) {
            if ('esST_URL' == $name) {
                // The actual content url is stored in metadata.
                $url = reset($value);
                $convertedResult->setActualURL($url);
                continue;
            }

            switch (strtoupper($name)) {
                case 'ES_CONTENTTYPE':
                    $systemName = strtoupper($name);
                    break;

                case 'ESST_FILENAME':
                    $systemName = strtoupper($name);
                    break;

                default:
                    list( , $systemName) = explode('_', $name, 2);
                    break;
            }

            $convertedResult->addResultMetadata($systemName, $value);
        }
    }
}
