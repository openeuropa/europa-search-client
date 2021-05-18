<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Contract\TokenInterface;
use OpenEuropa\EuropaSearchClient\Model\TokenResult;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\Token
 */
class TokenTest extends TestCase
{
    use ClientTestTrait;

    /**
     * @covers ::getToken
     * @dataProvider providerTestToken
     *
     * @param array $clientConfig
     * @param mixed $response
     * @param mixed $expectedResult
     */
    public function testToken(array $clientConfig, $response, $expectedResult): void
    {
        $client = $this->getTestingClient($clientConfig, [$response]);
        /** @var TokenInterface $tokenService */
        $tokenService = $client->getContainer()->get('token');
        $actualResult = $tokenService->getToken();
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
                    'tokenApiEndpoint' => 'http://example.com/token',
                    'consumerKey' => 'foo',
                    'consumerSecret' => 'bar',
                ],
                new Response(200, [], json_encode([
                    'access_token' => 'JWT_TOKEN',
                    'scope' => 'APPLICATION_SCOPE',
                    'token_type' => 'Bearer',
                    'expires_in' => 3600,
                ])),
                (new TokenResult())
                    ->setAccessToken('JWT_TOKEN')
                    ->setScope('APPLICATION_SCOPE')
                    ->setTokenType('Bearer')
                    ->setExpiresIn(3600),
            ],
        ];
    }
}
