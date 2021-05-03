<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a document transfer object.
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Document
{

    /**
     * If the document has access restrictions.
     *
     * @var bool
     */
    protected $accessRestriction;

    /**
     * The API version.
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * An array of children documents.
     *
     * @var \OpenEuropa\EuropaSearchClient\Model\Document[]
     */
    protected $children;

    /**
     * The document content.
     *
     * @var string
     */
    protected $content;

    /**
     * The document content type.
     *
     * @var string
     */
    protected $contentType;

    /**
     * @var string|null
     */
    protected $database;

    /**
     * @var string|null
     */
    protected $databaseLabel;

    /**
     * @var string|null
     */
    protected $groupById;

    /**
     * The document language.
     *
     * @var string
     */
    protected $language;

    /**
     * The document metadata.
     *
     * It consists of a nested array where the first level contains the field name
     * as key and the value is a list of values for the field itself.
     *
     * @var array
     */
    protected $metadata;

    /**
     * The number of pages in the document.
     *
     * @var int|null
     */
    protected $pages;

    /**
     * The document unique reference.
     *
     * @var string
     */
    protected $reference;

    /**
     * The document summary.
     *
     * @var string|null
     */
    protected $summary;

    /**
     * The document title.
     *
     * @var string|null
     */
    protected $title;

    /**
     * The document url.
     *
     * @var string
     */
    protected $url;

    /**
     * The document weight.
     *
     * @var float
     */
    protected $weight;

    /**
     * Returns if the document has access restrictions.
     *
     * @return bool
     *   True if document has access restrictions, false otherwise.
     */
    public function hasAccessRestriction(): bool
    {
        return $this->accessRestriction;
    }

    /**
     * Sets if the document has access restrictions.
     *
     * @param bool $accessRestriction
     *   A boolean value.
     */
    public function setAccessRestriction(bool $accessRestriction): void
    {
        $this->accessRestriction = $accessRestriction;
    }

    /**
     * Returns the API version.
     *
     * @return string
     *   The API version.
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Sets the API version.
     *
     * @param string $apiVersion
     *   The API version.
     */
    public function setApiVersion(string $apiVersion): void
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * Returns the list of children documents.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Document[]
     *   An array of documents.
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Sets the children documents.
     *
     * @param \OpenEuropa\EuropaSearchClient\Model\Document[] $children
     *   The children documents.
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    /**
     * Returns the document content.
     *
     * @return string
     *   The document content.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets the document content.
     *
     * @param string $content
     *   The document content.
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Returns the document content type.
     *
     * @return string
     *   The document content type.
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Sets the document content type.
     *
     * @param string $contentType
     *   The document content type.
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string|null
     */
    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * @param string|null $database
     */
    public function setDatabase(?string $database): void
    {
        $this->database = $database;
    }

    /**
     * @return string|null
     */
    public function getDatabaseLabel(): ?string
    {
        return $this->databaseLabel;
    }

    /**
     * @param string|null $databaseLabel
     */
    public function setDatabaseLabel(?string $databaseLabel): void
    {
        $this->databaseLabel = $databaseLabel;
    }

    /**
     * @return string|null
     */
    public function getGroupById(): ?string
    {
        return $this->groupById;
    }

    /**
     * @param string|null $groupById
     */
    public function setGroupById(?string $groupById): void
    {
        $this->groupById = $groupById;
    }

    /**
     * Returns the document language.
     *
     * @return string
     *   The document language.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Sets the document language.
     *
     * @param string $language
     *   The document language.
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * Returns the document metadata.
     *
     * @return array
     *   A nested array of field names and values.
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Sets the document metadata.
     *
     * @param array $metadata
     *   A nested array of field names and values.
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * Returns the number of pages in the document.
     *
     * @return int|null
     *   The number of pages in the document.
     */
    public function getPages(): ?int
    {
        return $this->pages;
    }

    /**
     * Sets the number of pages in the document.
     *
     * @param int|null $pages
     *   The number of pages in the document.
     */
    public function setPages(?int $pages): void
    {
        $this->pages = $pages;
    }

    /**
     * Returns the document unique reference.
     *
     * @return string
     *   The document unique reference.
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Sets the document unique reference.
     *
     * @param string $reference
     *   The document unique reference.
     */
    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * Returns the document summary.
     *
     * @return string|null
     *   The document summary.
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * Sets the document summary.
     *
     * @param string|null $summary
     *   The document summary.
     */
    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * Returns the document title.
     *
     * @return string|null
     *   The document title.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the document title.
     *
     * @param string|null $title
     *   The document title.
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Returns the document url.
     *
     * @return string
     *   The document url.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Sets the document url.
     *
     * @param string $url
     *   The document url.
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Returns the document weight.
     *
     * @return float
     *   The document weight.
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * Sets the document weight.
     *
     * @param float $weight
     *   The document weight.
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }
}
