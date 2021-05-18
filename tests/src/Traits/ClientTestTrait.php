<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use Http\Factory\Guzzle\UriFactory;
use OpenEuropa\EuropaSearchClient\Client;
use OpenEuropa\EuropaSearchClient\Contract\ClientInterface;

trait ClientTestTrait
{
    /**
     * @param array $configuration
     * @param array $responseQueue
     * @return ClientInterface
     */
    protected function getTestingClient(array $configuration = [], array $responseQueue = []): ClientInterface
    {
        $mock = new MockHandler($responseQueue);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        $requestFactory = new RequestFactory();
        $streamFactory = new StreamFactory();
        $uriFactory = new UriFactory();
        return new Client(
            $httpClient,
            $requestFactory,
            $streamFactory,
            $uriFactory,
            $configuration
        );
    }
}
