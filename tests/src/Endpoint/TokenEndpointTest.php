<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Endpoint;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Endpoint\TokenEndpoint;
use OpenEuropa\EuropaSearchClient\Model\Token;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\AssertTestRequestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Endpoint\TokenEndpoint
 */
class TokenEndpointTest extends TestCase
{
    use ClientTestTrait;
    use AssertTestRequestTrait;

    /**
     * @dataProvider providerTestToken
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testToken(array $clientConfig, array $responses, $expectedResult): void
    {
        $client = $this->getTestingClient($clientConfig, $responses);
        $container = $this->getClientContainer($client);

        $this->assertEquals($expectedResult, $container->get('token')->execute());
        $this->assertCount(1, $this->clientHistory);
        $request = $this->clientHistory[0]['request'];
        $this->assertTokenRequest($request);
    }

    /**
     * @dataProvider providerTestInvalidConfig
     */
    public function testInvalidConfig($consumerKey, $consumerSecret, string $exceptionMessage): void
    {
        $this->expectExceptionObject(new InvalidOptionsException($exceptionMessage));
        new TokenEndpoint('http://example.com/token', [
            'consumerKey' => $consumerKey,
            'consumerSecret' => $consumerSecret,
        ]);
    }

    public function testMissingConfig(): void
    {
        $this->expectExceptionObject(new MissingOptionsException('The required options "consumerKey", "consumerSecret" are missing.'));
        new TokenEndpoint('http://example.com/token');
    }

    public function testDefinedConfig(): void
    {
        $this->expectExceptionObject(new UndefinedOptionsException('The option "foo" does not exist. Defined options are: "consumerKey", "consumerSecret", "endpointUrl".'));
        new TokenEndpoint('http://example.com/token', [
            'foo' => 'bar',
        ]);
    }

    /**
     * @return array
     */
    public function providerTestToken(): array
    {
        return [
            'simple token call' => [
                [
                    'tokenApiEndpoint' => 'http://example.com/token',
                    'consumerKey' => 'baz',
                    'consumerSecret' => 'qux',
                ],
                [
                    new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/json/simple_token_call_response.json'))
                ],
                (new Token())
                    ->setAccessToken('JWT_TOKEN')
                    ->setScope('APPLICATION_SCOPE')
                    ->setTokenType('Bearer')
                    ->setExpiresIn(3600),
            ],
        ];
    }

    public function providerTestInvalidConfig(): array
    {
        return [
            'wrong "apiKey" format' => [
                1,
                'consumerSecret',
                'The option "consumerKey" with value 1 is expected to be of type "string", but is of type "integer".',
            ],
            'wrong "database" format' => [
                'consumerKey',
                1,
                'The option "consumerSecret" with value 1 is expected to be of type "string", but is of type "integer".',
            ],
        ];
    }
}
