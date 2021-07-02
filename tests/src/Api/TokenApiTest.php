<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Model\Token;
use OpenEuropa\Tests\EuropaSearchClient\HistoryMiddleware;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\InspectTestRequestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\TokenApi
 */
class TokenApiTest extends TestCase
{
    use ClientTestTrait;
    use InspectTestRequestTrait;

    /**
     * @dataProvider providerTestToken
     *
     * @param array $clientConfig
     * @param mixed $expectedResult
     */
    public function testToken(array $clientConfig, $expectedResult): void
    {
        $historyMiddleware = new HistoryMiddleware();
        $actualResult = $this->getTestingClient($clientConfig, $historyMiddleware)
            ->getContainer()->get('token')
            ->getToken();
        $request = $historyMiddleware->getLastHistoryEntry()['request'];
        $this->inspectTokenRequest($request);
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function providerTestToken(): array
    {
        return [
            'simple token call' => [
                [
                    'tokenApiEndpoint' => 'http://web:8080/tests/fixtures/json/token.json',
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
}
