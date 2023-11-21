<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * The reason of having a base class for Document is that 3rd-party code can use
 * it to provide document ingestion models.
 */
abstract class DocumentBase
{

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array[]
     * @todo Convert to Metadata model in OEL-188
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-188
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $content;

    /**
     * The document language.
     *
     * @var string
     */
    protected $language;

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param array $metadata
     * @return $this
     * @todo Convert to Metadata model in OEL-188
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-188
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @return array
     * @todo Convert to Metadata model in OEL-188
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-188
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param string|null $content
     * @return $this
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): string|null
    {
        return $this->content;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}
