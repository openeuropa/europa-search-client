<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Traits;

use Psr\Http\Message\RequestInterface;

trait InspectTestRequestTrait
{
    /**
     * @var string
     */
    protected $boundary;

    /**
     * @param RequestInterface $request
     */
    protected function inspectTokenRequest(RequestInterface $request): void
    {
        $this->assertEquals('http://example.com/token', $request->getUri());
        $this->assertSame('Basic YmF6OnF1eA==', $request->getHeaderLine('Authorization'));
        $this->assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertSame('grant_type=client_credentials', $request->getBody()->getContents());
    }

    /**
     * @param RequestInterface $request
     */
    protected function inspectAuthorizationHeaders(RequestInterface $request): void
    {
        $this->assertSame('Bearer JWT_TOKEN', $request->getHeaderLine('Authorization'));
        $this->assertSame('JWT_TOKEN', $request->getHeaderLine('Authorization-propagation'));
    }

    /**
     * @param RequestInterface $request
     * @param string $boundary
     */
    protected function assertBoundary(RequestInterface $request, string $boundary): void
    {
        $this->assertSame('multipart/form-data; boundary="' . $boundary . '"', $request->getHeaderLine('Content-Type'));
    }

    /**
     * @param RequestInterface $request
     * @return string
     *    The boundary.
     */
    protected function getBoundary(RequestInterface $request): string
    {
        preg_match('/; boundary="([^"].*)"/', $request->getHeaderLine('Content-Type'), $found);
        return $found[1];
    }

    /**
     * @param RequestInterface $request
     * @param string $boundary
     * @return false|string[]
     */
    protected function getMultiParts(RequestInterface $request, string $boundary)
    {
        $parts = explode("--{$boundary}", $request->getBody()->getContents());
        array_shift($parts);
        array_pop($parts);

        return $parts;
    }

    /**
     * @param string $part
     * @param string $contentType
     * @param string $name
     * @param int $length
     * @param string $expected_content
     */
    protected function inspectPart(string $part, string $contentType, string $name, int $length, string $expected_content)
    {
        [$headers, $content] = explode("\r\n\r\n", $part);
        $headers = explode("\r\n", $headers);
        $this->assertContains("Content-Type: $contentType", $headers);
        $this->assertContains("Content-Disposition: form-data; name=\"$name\"; filename=\"$name\"", $headers);
        $this->assertContains("Content-Length: $length", $headers);
        $this->assertSame($expected_content . "\r\n", $content);
    }
}
