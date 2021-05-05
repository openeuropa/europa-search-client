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
     * Get the access token.
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Set the access token.
     *
     * @param string $accessToken
     *
     * @return $this
     */
    public function setAccessToken(string $accessToken): Token
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get the scope.
     *
     * @return mixed
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * Set the scope.
     *
     * @param string $scope
     *
     * @return $this
     */
    public function setScope(string $scope): Token
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get the token type.
     *
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * Set the token type.
     *
     * @param string $tokenType
     *
     * @return $this
     */
    public function setTokenType(string $tokenType): Token
    {
        $this->tokenType = $tokenType;

        return $this;
    }

    /**
     * Return the expiration period.
     *
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * Sets the expiration.
     *
     * @param int $expiresIn
     *
     * @return $this
     */
    public function setExpiresIn(int $expiresIn): Token
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }
}
