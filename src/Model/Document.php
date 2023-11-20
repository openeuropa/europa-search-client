<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Traits\ApiVersionAwareTrait;

/**
 * A class that represents a document transfer object.
 */
class Document extends DocumentBase
{
    use ApiVersionAwareTrait;

    /**
     * If the document has access restrictions.
     *
     * @var bool
     */
    protected $accessRestriction;

    /**
     * An array of children documents.
     *
     * @var \OpenEuropa\EuropaSearchClient\Model\Document[]
     */
    protected $children;

    /**
     * The document content.
     *
     * @var string|null
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
     * The number of pages in the document.
     *
     * @var int|null
     */
    protected $pages;

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
