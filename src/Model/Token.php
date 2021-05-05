<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a token data transfer object.
 */
class Token
{

    /**
     * JWT Access token
     *
     * @var string
     */
    protected $accessToken;

    /**
     * Scope of the access token
     *
     * @var string
     */
    protected $scope;

    /**
     * The type of the access token
     *
     * @var string
     */
    protected $tokenType;

    /**
     * Expiration of the access token in seconds
     *
     * @var int
     */
    protected $expiresIn;

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return mixed
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     * @return void
     */
    public function setScope(string $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @param string $tokenType
     */
    public function setTokenType(string $tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @param int $expiresIn
     */
    public function setExpiresIn(int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }
}
