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
     * @return $this
     */
    public function setAccessRestriction(bool $accessRestriction): self
    {
        $this->accessRestriction = $accessRestriction;
        return $this;
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
     * @return $this
     */
    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
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
     * @return $this
     */
    public function setChildren(array $children): self
    {
        $this->children = $children;
        return $this;
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
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
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
     * @return $this
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;
        return $this;
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
     * @return $this
     */
    public function setDatabase(?string $database): self
    {
        $this->database = $database;
        return $this;
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
     * @return $this
     */
    public function setDatabaseLabel(?string $databaseLabel): self
    {
        $this->databaseLabel = $databaseLabel;
        return $this;
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
     * @return $this
     */
    public function setGroupById(?string $groupById): self
    {
        $this->groupById = $groupById;
        return $this;
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
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
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
     * @return $this
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
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
     * @return $this
     */
    public function setPages(?int $pages): self
    {
        $this->pages = $pages;
        return $this;
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
     * @return $this
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
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
     * @return $this
     */
    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;
        return $this;
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
     * @return $this
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Returns the document URL.
     *
     * @return string
     *   The document url.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Sets the document URL.
     *
     * @param string $url
     *   The document url.
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
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
     * @return $this
     */
    public function setWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }
}
