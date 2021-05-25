<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use GuzzleHttp\Handler\MockHandler as GuzzleMockHandler;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;

class MockHandler extends GuzzleMockHandler
{
    /**
     * @var callable|null
     */
    protected $onRequest;

    /**
     * @inheritDoc
     */
    public function __construct(array $queue = null, callable $onFulfilled = null, callable $onRejected = null, callable $onRequest = null)
    {
        $this->onRequest = $onRequest;
        parent::__construct($queue, $onFulfilled, $onRejected);
    }

    /**
     * @inheritDoc
     */
    public function __invoke(RequestInterface $request, array $options): PromiseInterface
    {
        if ($this->onRequest) {
            ($this->onRequest)($request);
        }
        return parent::__invoke($request, $options);
    }
}
