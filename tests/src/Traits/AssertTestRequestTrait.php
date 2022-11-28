<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Traits;

use Psr\Http\Message\RequestInterface;

trait AssertTestRequestTrait
{
    /**
     * @var string
     */
    protected $boundary;

    /**
     * @param RequestInterface $request
     */
    protected function assertTokenRequest(RequestInterface $request): void
    {
        $this->assertEquals('http://example.com/token', $request->getUri());
        $this->assertSame('Basic YmF6OnF1eA==', $request->getHeaderLine('Authorization'));
        $this->assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertSame('grant_type=client_credentials', $request->getBody()->__toString());
    }

    /**
     * @param RequestInterface $request
     */
    protected function assertAuthorizationHeaders(RequestInterface $request): void
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
    protected function getRequestBoundary(RequestInterface $request): ?string
    {
        preg_match('/; boundary="([^"].*)"/', $request->getHeaderLine('Content-Type'), $found);
        return $found[1] ?? null;
    }

    /**
     * @param RequestInterface $request
     * @param string $boundary
     * @return false|string[]
     */
    protected function getRequestMultipartStreamResources(RequestInterface $request, string $boundary)
    {
        $parts = explode("--{$boundary}", $request->getBody()->getContents());
        // The first and last entries are empty.
        // @todo Improve this.
        array_shift($parts);
        array_pop($parts);

        return $parts;
    }

    protected function assertMultipartStreamResource(string $part, string $contentType, string $name, int $length, string $expected_content)
    {
        [$headers, $content] = explode("\r\n\r\n", $part);
        $headers = explode("\r\n", $headers);
        $this->assertContains("Content-Type: $contentType", $headers);
        $this->assertContains("Content-Disposition: form-data; name=\"$name\"; filename=\"$name\"", $headers);
        $this->assertContains("Content-Length: $length", $headers);
        $this->assertSame($expected_content . "\r\n", $content);
    }
}
