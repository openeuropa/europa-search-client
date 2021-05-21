<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface SearchApiBaseInterface extends ApiInterface, LanguagesAwareInterface
{
    /**
     * @param string|null $text
     *
     * @return $this
     */
    public function setText(?string $text): self;

    /**
     * @return string
     */
    public function getText(): string;

    /**
     * @param array|null $query
     *
     * @return $this
     */
    public function setQuery(?array $query): self;

    /**
     * @return array|null
     */
    public function getQuery(): ?array;

    /**
     * @param array|null $sort
     *
     * @return $this
     */
    public function setSort(?array $sort): self;

    /**
     * @return array|null
     */
    public function getSort(): ?array;

    /**
     * @param string|null $sessionToken
     *
     * @return $this
     */
    public function setSessionToken(?string $sessionToken): self;

    /**
     * @return string|null
     */
    public function getSessionToken(): ?string;
}