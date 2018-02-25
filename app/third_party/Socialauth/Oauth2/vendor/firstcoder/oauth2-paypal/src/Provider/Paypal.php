<?php namespace Firstcoder\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Paypal extends AbstractProvider
{
    /**
     * Default scopes
     *
     * @var array
     */
    public $defaultScopes = ['openid profile email address phone'];

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://api.paypal.com/v1/identity/openidconnect/tokenservice';
    }

    /**
     * Get default scopes
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return $this->defaultScopes;
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    { 
        if (isset($data['error'])) {
            throw new IdentityProviderException(
                $data['error_description'] ?: $response->getReasonPhrase(),
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
     * @return \League\OAuth2\Client\Provider\UserInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
		$user = new PaypalResourceOwner($response);
		return $user;   
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://api.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid&access_token='.$token;
    }


}
