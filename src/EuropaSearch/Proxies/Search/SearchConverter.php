<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\SearchConverter.
 */

namespace EC\EuropaSearch\Proxies\Search;

use EC\EuropaSearch\Messages\Search\SearchMessage;
use EC\EuropaSearch\Messages\Search\SearchRequest;
use EC\EuropaSearch\Messages\Search\SearchResponse;
use EC\EuropaSearch\Messages\Search\SearchResult;
use EC\EuropaSearch\Proxies\AbstractMessageConverter;
use EC\EuropaWS\Proxies\MessageConverterInterface;
use EC\EuropaWS\Common\WSConfigurationInterface;
use EC\EuropaWS\Exceptions\ProxyException;
use EC\EuropaWS\Messages\ValidatableMessageInterface;

/**
 * Class SearchConverter.
 *
 * Converter for SearchMessage object.
 *
 * @package EC\EuropaSearch\Proxies\Search
 */
class SearchConverter extends AbstractMessageConverter
{

    /**
     * {@inheritDoc}
     */
    public function convertMessage(ValidatableMessageInterface $message, WSConfigurationInterface $configuration)
    {
        throw new ProxyException('The "convertMessage()" method is not supported.');
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageWithComponents(ValidatableMessageInterface $message, array $convertedComponent, WSConfigurationInterface $configuration)
    {

        $request = new SearchRequest();

        $parameter = $message->getSearchedLanguages();
        $request->setLanguages($parameter);

        $parameter = $message->getHighLightLimit();
        $request->setHighlightLimit($parameter);

        $parameter = $message->getHighlightRegex();
        $request->setHighlightRegex($parameter);

        $parameter = $message->getPaginationLocation();
        $request->setPageNumber($parameter);

        $parameter = $message->getPaginationSize();
        $request->setPageSize($parameter);

        $parameter = $message->getSessionToken();
        $request->setSessionToken($parameter);

        // Build the final sort value to send to the service.
        $sort = $message->getSortField();
        if (!empty($sort)) {
            $sortDirection = ($message->getSortDirection()) ?: SearchMessage::SEARCH_SORT_ASC;
            $sort .= ':'.$sortDirection;
            $request->setSort($sort);
        }

        $request->setText($message->getSearchedText());

        if (!empty($convertedComponent)) {
            $injectedComponent = reset($convertedComponent);
            $request->addConvertedComponents($injectedComponent);
        }

        // Data retrieved from the web services configuration.
        $WSSettings = $configuration->getConnectionConfig();
        $request->setAPIKey($WSSettings['APIKey']);

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function convertMessageResponse($response, WSConfigurationInterface $configuration)
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
     * @param SearchResponse $convertedResponse
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
     * @param SearchResponse $convertedResponse
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
     * @param SearchResponse $convertedResponse
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
     * @return SearchResult
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
     * @param SearchResult $convertedResult
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
     * @param SearchResult $convertedResult
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

            list( , $systemName) = explode('_', $name, 2);
            $convertedResult->addResultMetadata($systemName, $value);
        }
    }
}
