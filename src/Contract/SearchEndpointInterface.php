<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\Sort;

interface SearchEndpointInterface extends SearchEndpointBaseInterface
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
     * @param Sort $sort
     * @return $this
     */
    public function setSort(Sort $sort): self;

    /**
     * @return Sort
     */
    public function getSort(): Sort;
}
