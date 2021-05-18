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
     */
    public function testSearch(): void
    {
        $client = $this->getTestingClient(
            [
                'tokenApiEndpoint' => 'http://example.com/token',
                'consumerKey' => 'foo',
                'consumerSecret' => 'bar',
            ],
            [
                new Response(200, [], '{"access_token":"jwt_token","scope":"application_scope","token_type":"Bearer","expires_in":3600}'),
            ]
        );

        /** @var TokenInterface $tokenService */
        $tokenService = $client->getContainer()->get('token');
        $result = $tokenService->getToken();

        $this->assertInstanceOf(TokenResult::class, $result);
        $this->assertSame('jwt_token', $result->getAccessToken());
        $this->assertSame('application_scope', $result->getScope());
        $this->assertSame('Bearer', $result->getTokenType());
        $this->assertSame(3600, $result->getExpiresIn());
    }
}
