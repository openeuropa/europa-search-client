<?php

namespace EC\EuropaSearch\Messages\Search;

/**
 * Class SearchResult.
 *
 * It represents a result of an Europa Search search.
 *
 * @package EC\EuropaSearch\Messages\Search
 */
class SearchResult
{

    /**
     * The result id.
     *
     * @var string
     */
    protected $resultReference;

    /**
     * The URL for displaying the result content via Europa Search.
     *
     * @var string
     */
    protected $europaSearchURL;

    /**
     * The URL of the actual result content.
     *
     * @var string
     */
    protected $actualURL;

    /**
     * The result content type as defined in Europa Search.
     *
     * @var string
     */
    protected $contentType;

    /**
     * The label of database as defined by Europa Search.
     *
     * @var string
     */
    protected $databaseLabel;

    /**
     * The database id as defined by Europa Search.
     * @var string
     */
    protected $database;

    /**
     * The summary of the result content.
     *
     * @var string
     */
    protected $resultSummary;

    /**
     * The sorting weight of the result in the list.
     *
     * @var float
     */
    protected $sortingWeight;

    /**
     * The full content of the result.
     *
     * @var string
     */
    protected $resultFullContent;

    /**
     * Flag indicated if the access is restricted for this result.
     *
     * @var boolean
     */
    protected $isAccessRestricted = false;

    /**
     * The indexed metadata for the result.
     *
     * @var array
     */
    protected $resultMetadata = [];

    /**
     * Gets the result id.
     *
     * @return string
     *   The result id.
     */
    public function getResultReference()
    {
        return $this->resultReference;
    }

    /**
     * Sets the result id.
     *
     * @param string $resultReference
     *   The result id.
     */
    public function setResultReference($resultReference)
    {
        $this->resultReference = $resultReference;
    }

    /**
     * Gets the URL for displaying the result content via Europa Search.
     *
     * @return string
     *   The Europa Search URL.
     */
    public function getEuropaSearchURL()
    {
        return $this->europaSearchURL;
    }

    /**
     * Sets the URL for displaying the result content via Europa Search.
     *
     * @param string $europaSearchURL
     *   The Europa Search URL.
     */
    public function setEuropaSearchURL($europaSearchURL)
    {
        $this->europaSearchURL = $europaSearchURL;
    }

    /**
     * Gets the URL of the actual result content.
     *
     * @return string
     *  The actual URL.
     */
    public function getActualURL()
    {
        return $this->actualURL;
    }

    /**
     * Sets the URL of the actual result content.
     *
     * @param string $actualURL
     *  The actual URL.
     */
    public function setActualURL($actualURL)
    {
        $this->actualURL = $actualURL;
    }

    /**
     * Gets the result content type as defined in Europa Search.
     *
     * @return string
     *   The result content type.
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Sets the result content type as defined in Europa Search.
     *
     * @param string $contentType
     *   The result content type.
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Gets the label of database as defined by Europa Search.
     *
     * @return string
     *   The label of database.
     */
    public function getDatabaseLabel()
    {
        return $this->databaseLabel;
    }

    /**
     * Sets the label of database as defined by Europa Search.
     *
     * @param string $databaseLabel
     *   The label of database.
     */
    public function setDatabaseLabel($databaseLabel)
    {
        $this->databaseLabel = $databaseLabel;
    }

    /**
     * Gets the database id as defined by Europa Search.
     *
     * @return string
     *   The database id.
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Sets the database id as defined by Europa Search.
     *
     * @param string $database
     *   The database id.
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * Gets the summary of the result content.
     *
     * @return string
     *   The summary of the result content.
     */
    public function getResultSummary()
    {
        return $this->resultSummary;
    }

    /**
     * Sets the summary of the result content.
     *
     * @param string $resultSummary
     *   The summary of the result content.
     */
    public function setResultSummary($resultSummary)
    {
        $this->resultSummary = $resultSummary;
    }

    /**
     * Gets the sorting weight of the result in the list.
     *
     * @return float
     *   The sorting weight.
     */
    public function getSortingWeight()
    {
        return $this->sortingWeight;
    }

    /**
     * Sets the sorting weight of the result in the list.
     *
     * @param float $sortingWeight
     *   The sorting weight.
     */
    public function setSortingWeight($sortingWeight)
    {
        $this->sortingWeight = $sortingWeight;
    }

    /**
     * Gets the full content of the result.
     *
     * @return string
     *   The full content.
     */
    public function getResultFullContent()
    {
        return $this->resultFullContent;
    }

    /**
     * Sets the full content of the result.
     *
     * @param string $resultFullContent
     *   The full content.
     */
    public function setResultFullContent($resultFullContent)
    {
        $this->resultFullContent = $resultFullContent;
    }

    /**
     * Indicates if the result content access is restricted.
     *
     * @return boolean
     *   True if the access is restricted; otherwise false;
     */
    public function isIsAccessRestricted()
    {
        return $this->isAccessRestricted;
    }

    /**
     * Sets the flag indicated if the access is restricted for this result.
     *
     * @param boolean $isAccessRestricted
     *   The flag value.
     */
    public function setIsAccessRestricted($isAccessRestricted)
    {
        $this->isAccessRestricted = $isAccessRestricted;
    }

    /**
     * Gets the indexed metadata for the result.
     *
     * @return array
     *   Array of Metadata.
     */
    public function getResultMetadata()
    {
        return $this->resultMetadata;
    }

    /**
     * Sets the indexed metadata for the result.
     *
     * @param array $resultMetadata
     *   Array of Metadata.
     */
    public function setResultMetadata(array $resultMetadata)
    {
        $this->resultMetadata = $resultMetadata;
    }

    /**
     * Adds a metadata to the list.
     *
     * @param string $systemName
     *   The metadata name as know by the client consumer system.
     * @param mixed  $value
     *   The set metadata value.
     */
    public function addResultMetadata($systemName, $value)
    {
        $this->resultMetadata[$systemName] = $value;
    }
}
