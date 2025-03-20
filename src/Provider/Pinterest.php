<?php

namespace Aporat\OAuth2\Client\Provider;

use Aporat\OAuth2\Client\Provider\Exception\PinterestIdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

final class Pinterest extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Domain
     *
     * @var string
     */
    public string $domain = 'https://pinterest.com';

    /**
     * Api domain
     *
     * @var string
     */
    public string $apiDomain = 'https://api.pinterest.com/v5';

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->domain . '/oauth';
    }

    /**
     * Get access token url to retrieve token
     *
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->apiDomain . '/oauth/token';
    }

    /**
     * Get provider url to fetch user details
     *
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->apiDomain . '/user_account';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes(): array
    {
        return ['user_accounts:read'];
    }

    /**
     * @inheritdoc
     */
    public function getAccessToken($grant, array $options = []): AccessTokenInterface
    {
        $options['continuous_refresh'] = 'true';
        return parent::getAccessToken($grant, $options);
    }

    /**
     * Check a provider response for errors.
     *
     * @param ResponseInterface $response
     * @param array $data Parsed response data
     * @return void
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw new PinterestIdentityProviderException(
                $data['message'] ?? $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return PinterestResourceOwner
     */
    protected function createResourceOwner(
        array $response,
        AccessToken $token
    ): PinterestResourceOwner {
        return new PinterestResourceOwner($response);
    }

    /**
     * Returns the default headers used by this provider.
     *
     * @return array
     */
    protected function getDefaultHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
        ];
    }
}
