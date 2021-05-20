<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\SearchBaseInterface;
use OpenEuropa\EuropaSearchClient\Traits\LanguagesAwareTrait;
use Psr\Http\Message\UriInterface;

abstract class SearchBase extends ApiBase implements SearchBaseInterface
{
    use LanguagesAwareTrait;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $query;

    /**
     * @var array
     */
    protected $sort;

    /**
     * @var string
     */
    protected $sessionToken;

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'apiKey' => $this->getRequiredStringSchema(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getRequestUriQuery(UriInterface $uri): array
    {
        $query = parent::getRequestUriQuery($uri);

        $query['apiKey'] = $this->getConfigValue('apiKey');
        $query['text'] = $this->getText();

        if ($sessionToken = $this->getSessionToken()) {
            $query['sessionToken'] = $sessionToken;
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    protected function getRequestMultipartStreamElements(): array
    {
        $parts = [];

        if ($languages = $this->getLanguages()) {
            $parts['languages'] = $this->jsonEncoder->encode($languages, 'json');
        }
        if ($query = $this->getQuery()) {
            $parts['query'] = $this->jsonEncoder->encode($query, 'json');
        }
        if ($sort = $this->getSort()) {
            $parts['sort'] = $this->jsonEncoder->encode($sort, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function setText(?string $text): SearchBaseInterface
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        // The special case '***' means 'Give me all the results'.
        return $this->text ?? '***';
    }

    /**
     * @inheritDoc
     */
    public function setQuery(?array $query): SearchBaseInterface
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): ?array
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function setSort(?array $sort): SearchBaseInterface
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSort(): ?array
    {
        return $this->sort;
    }

    /**
     * @inheritDoc
     */
    public function setSessionToken(?string $sessionToken): SearchBaseInterface
    {
        $this->sessionToken = $sessionToken;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSessionToken(): ?string
    {
        return $this->sessionToken;
    }
}
