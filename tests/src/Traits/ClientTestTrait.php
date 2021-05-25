<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use Http\Factory\Guzzle\UriFactory;
use OpenEuropa\EuropaSearchClient\Client;
use OpenEuropa\EuropaSearchClient\Contract\ClientInterface;
use OpenEuropa\Tests\EuropaSearchClient\MockHandler;

trait ClientTestTrait
{
    /**
     * @param array $configuration
     * @param array $responseQueue
     * @param callable|null $onRequest
     * @return ClientInterface
     */
    protected function getTestingClient(
        array $configuration = [],
        array $responseQueue = [],
        ?callable $onRequest = null
    ): ClientInterface {
        $mock = new MockHandler($responseQueue, null, null, $onRequest);
        $handlerStack = HandlerStack::create($mock);
        return new Client(
            new HttpClient(['handler' => $handlerStack]),
            new RequestFactory(),
            new StreamFactory(),
            new UriFactory(),
            $configuration
        );
    }
}
