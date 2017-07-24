<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\Transmission.
 */

namespace EC\EuropaSearch\Index\Transmission;

/**
 * Class IndexingRequest.
 *
 * It represents a document indexing request ready to be transmitted.
 *
 * @package EC\EuropaSearch\Index\Transmission
 */
class IndexingRequest
{
    /**
     * APIKey to use with the index request.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Database to use with the index request.
     *
     * @var string
     */
    private $dataBase;

    /**
     * The uri of the document to index.
     *
     * @var string
     */
    private $uri;

    /**
     * Flag determining if the document to index is a file are a web content.
     *
     * @var
     */
    private $isFile;

    /**
     * Language of the document to index.
     *
     * @var string
     */
    private $language = 'en';

    /**
     * The reference identifying the document to index.
     *
     * @var string
     */
    private $reference;

    /**
     * JSON of the metadata of the document to index.
     * @var string
     */
    private $metadataJSON;

    /**
     * Content of the document to index, if it is not a file.
     *
     * @var string
     */
    private $content;

    /**
     * IndexingRequest constructor.
     *
     * @param string $apiKey
     *   The APIKey to use for the Europa Search service.
     * @param string $dataBase
     *   The database id to use for the Europa Search service.
     * @param string $reference
     *   The document reference to use for the Europa Search service.
     */
    public function __construct($apiKey, $dataBase, $reference)
    {
        $this->apiKey = $apiKey;
        $this->dataBase = $dataBase;
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getDataBase()
    {
        return $this->dataBase;
    }

    /**
     * @param string $dataBase
     */
    public function setDataBase($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function isFile()
    {
        return $this->isFile;
    }

    /**
     * @param mixed $isFile
     */
    public function setIsFile($isFile)
    {
        $this->isFile = $isFile;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getMetadataJSON()
    {
        return $this->metadataJSON;
    }

    /**
     * @param string $metadataJSON
     */
    public function setMetadataJSON($metadataJSON)
    {
        $this->metadataJSON = $metadataJSON;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
