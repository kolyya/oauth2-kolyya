<?php

namespace Kolyya\OAuth2Client\Provider;

use Kolyya\OAuth2Client\Provider\Exception\KolyyaAccessDeniedException;
use Kolyya\OAuth2Client\Token\KolyyaAccessToken;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Kolyya extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected string $serverUrl;

    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->serverUrl = $options['server_url'];

        parent::__construct($options, $collaborators);
    }

    public function getBaseAuthorizationUrl(): string
    {
        return $this->serverUrl . '/authorize';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->serverUrl . '/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->serverUrl . '/api/me';
    }

    protected function createAccessToken(array $response, AbstractGrant $grant): KolyyaAccessToken
    {
        return new KolyyaAccessToken($response);
    }

    protected function getDefaultScopes(): array
    {
        return ['email'];
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        // Metadata info
        $contentTypeRaw = $response->getHeader('Content-Type');
        $contentTypeArray = explode(';', reset($contentTypeRaw));
        $contentType = reset($contentTypeArray);

        // Data info
        $responseCode = $response->getStatusCode();
        $responseMessage = $response->getReasonPhrase();
        $errorDescription = $data['error_description'] ?? null;
        $errorMessage = $data['message'] ?? $errorDescription;
        $message = $errorMessage ?: $responseMessage;

        if (200 !== $responseCode) {
            throw new KolyyaAccessDeniedException($message, $responseCode, $data);
        }

        // Content validation
        if ('application/json' !== $contentType && 'application/ld+json' !== $contentType) {
            throw new KolyyaAccessDeniedException($message, $responseCode, $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): KolyyaResourceOwner
    {
        return new KolyyaResourceOwner($response);
    }
}
