<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface SearchApiInterface extends SearchApiBaseInterface
{
    /**
     * @param int|null $pageNumber
     *
     * @return $this
     */
    public function setPageNumber(?int $pageNumber): self;

    /**
     * @return int|null
     */
    public function getPageNumber(): ?int;

    /**
     * @param int|null $pageSize
     *
     * @return $this
     */
    public function setPageSize(?int $pageSize): self;

    /**
     * @return int|null
     */
    public function getPageSize(): ?int;

    /**
     * @param string|null $highlightRegex
     *
     * @return $this
     */
    public function setHighlightRegex(?string $highlightRegex): self;

    /**
     * @return string|null
     */
    public function getHighlightRegex(): ?string;

    /**
     * @param int|null $highlightLimit
     *
     * @return $this
     */
    public function setHighlightLimit(?int $highlightLimit): self;

    /**
     * @return int|null
     */
    public function getHighlightLimit(): ?int;

    /**
     * @param array|null $sort
     * @return $this
     * @todo Create a Sort model in OEL-173
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-173
     */
    public function setSort(?array $sort): self;

    /**
     * @return array|null
     * @todo Create a Sort model in OEL-173
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-173
     */
    public function getSort(): ?array;
}
