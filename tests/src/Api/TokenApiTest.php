<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Token;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\InspectTestRequestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

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
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testToken(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses, [$this, 'inspectRequest'])
            ->getContainer()->get('token')
            ->getToken();
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param RequestInterface $request
     */
    public function inspectRequest(RequestInterface $request): void
    {
        $this->inspectTokenRequest($request);
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
                    new Response(200, [], json_encode([
                        'access_token' => 'JWT_TOKEN',
                        'scope' => 'APPLICATION_SCOPE',
                        'token_type' => 'Bearer',
                        'expires_in' => 3600,
                    ]))
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
