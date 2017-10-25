<?php

namespace EC\EuropaSearch\Transporters\Requests\Search;

use EC\EuropaSearch\Transporters\Requests\AbstractRequest;

/**
 * Class SearchRequest.
 *
 * It defines a search request that is sent for indexing to the Europa Search
 * services.
 *
 * @package EC\EuropaSearch\Transporters\Requests\Search
 */
class SearchRequest extends AbstractRequest
{

    /**
     * Gets the search query in a JSON format.
     *
     * @return string
     *   The JSON query.
     */
    public function getQueryJSON()
    {
        return $this->body['query']['contents'];
    }

    /**
     * Sets the search query in a JSON format.
     *
     * @param string $queryJSON
     *   The JSON query.
     */
    public function setQueryJSON($queryJSON)
    {
        $this->body['query'] = [
            'name' => 'query',
            'contents' => $queryJSON,
            'headers' => ['content-type' => 'application/json'],
        ];
    }

    /**
     * Gets the searched text.
     *
     * @return string
     *   The searched text.
     */
    public function getText()
    {
        return $this->body['text']['contents'];
    }

    /**
     * Sets the searched text.
     *
     * @param string $text
     *   The searched text.
     */
    public function setText($text)
    {
        $this->body['text'] = [
            'name' => 'text',
            'contents' => $text,
        ];
    }

    /**
     * Gets the list of languages implied in the search.
     *
     * @return array
     *   The list of implied languages
     */
    public function getLanguages()
    {
        return $this->body['languages']['contents'];
    }

    /**
     * Sets the list of languages implied in the search.
     *
     * @param array $languages
     *   The list of implied languages
     */
    public function setLanguages(array $languages)
    {
        $this->body['languages'] = [
            'name' => 'languages',
            'contents' => json_encode($languages),
            'headers' => ['content-type' => 'application/json'],
        ];
    }

    /**
     * Gets the page number.
     *
     * @return int
     *   The page number.
     */
    public function getPageNumber()
    {
        return $this->query['pageNumber'];
    }

    /**
     * Sets the page number.
     *
     * @param int $pageNumber
     *   The page number.
     */
    public function setPageNumber($pageNumber)
    {
        $this->query['pageNumber'] = $pageNumber;
    }

    /**
     * Gets the number of results to retrieve.
     *
     * @return int
     *   The number of results to retrieve.
     */
    public function getPageSize()
    {
        return $this->query['pageSize'];
    }

    /**
     * Sets the number of results to retrieve.
     *
     * @param int $pageSize
     *   The number of results to retrieve.
     */
    public function setPageSize($pageSize)
    {
        $this->query['pageSize'] = $pageSize;
    }

    /**
     * Gets the regular expression to use to highlight result texts.
     *
     * @return string
     *   The regular expression to use.
     */
    public function getHighlightRegex()
    {
        return $this->query['highlightRegex'];
    }

    /**
     * Sets the regular expression to use to highlight result texts.
     *
     * @param string $highlightRegex
     *   The regular expression to use.
     */
    public function setHighlightRegex($highlightRegex)
    {
        $this->query['highlightRegex'] = $highlightRegex;
    }

    /**
     * Gets the length of the highlighted text.
     *
     * @return int
     *   The length of the highlighted text.
     */
    public function getHighlightLimit()
    {
        return $this->query['highlightLimit'];
    }

    /**
     * Sets the length of the highlighted text.
     *
     * @param int $highlightLimit
     *   The length of the highlighted text.
     */
    public function setHighlightLimit($highlightLimit)
    {
        $this->query['highlightLimit'] = $highlightLimit;
    }

    /**
     * Gets the session token to use with search (if restricted).
     *
     * @return string
     *  The session token to use.
     */
    public function getSessionToken()
    {
        return $this->query['sessionToken'];
    }

    /**
     * Sets the session token to use with search (if restricted).
     *
     * @param string $sessionToken
     *  The session token to use.
     */
    public function setSessionToken($sessionToken)
    {
        $this->query['sessionToken'] = $sessionToken;
    }

    /**
     * Gets the sort criteria to apply with the search request.
     *
     * @return string
     *   The sort criteria to apply.
     *
     */
    public function getSort()
    {
        return $this->body['sort']['contents'];
    }

    /**
     * Sets the sort criteria to apply with the search request.
     *
     * @param string $sort
     *   The sort criteria to apply.
     */
    public function setSort($sort)
    {
        $this->body['sort'] = [
            'name' => 'sort',
            'contents' => $sort,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function addConvertedComponents(array $components)
    {

        $json = json_encode($components);
        $this->setQueryJSON($json);
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestMethod()
    {
        return 'POST';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestURI()
    {
        return '/es/search-api/rest/search';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestOptions()
    {
        return [
            'multipart' => $this->getRequestBody(),
            'query' => $this->getRequestQuery(),
        ];
    }
}
