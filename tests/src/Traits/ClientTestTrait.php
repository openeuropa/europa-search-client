<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\HttpFactory;
use OpenEuropa\EuropaSearchClient\Client;
use OpenEuropa\EuropaSearchClient\Contract\ClientInterface;

trait ClientTestTrait
{
    /**
     * @var array
     */
    protected $clientHistory = [];

    /**
     * @param array $configuration
     * @param array $responseQueue
     * @return ClientInterface
     */
    protected function getTestingClient(array $configuration = [], array $responseQueue = []): ClientInterface
    {
        $handlerStack = HandlerStack::create(new MockHandler($responseQueue));
        $handlerStack->push(Middleware::history($this->clientHistory));

        $httpFactory = new HttpFactory();
        return new Client(
            new HttpClient(['handler' => $handlerStack]),
            $httpFactory,
            $httpFactory,
            $httpFactory,
            $configuration
        );
    }

    /**
     * @param ClientInterface $client
     * @return mixed
     */
    protected function getClientContainer(ClientInterface $client)
    {
        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('container');
        $property->setAccessible(true);
        return $property->getValue($client);
    }
}
