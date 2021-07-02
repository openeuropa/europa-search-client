<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use GuzzleHttp\Middleware;

/**
 * An HTTP client middleware that wraps the Guzzle history middleware.
 *
 * Note: this service has state! It should be only used for test purposes.
 * It will keep track of the history of requests and responses across all the
 * HTTP client instances.
 */
class HistoryMiddleware
{

    /**
     * @var array
     */
    protected $historyContainer = [];

    /**
     * Wraps the history middleware for using in Drupal.
     */
    public function __invoke()
    {
        return Middleware::history($this->historyContainer);
    }

    /**
     * @return array
     */
    public function getHistoryContainer(): array
    {
        return $this->historyContainer;
    }

    /**
     * @return array|bool
     */
    public function getLastHistoryEntry(): ?array
    {
        return end($this->historyContainer);
    }
}
