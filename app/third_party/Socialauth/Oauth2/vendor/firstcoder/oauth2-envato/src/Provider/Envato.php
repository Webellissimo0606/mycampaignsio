<?php namespace Firstcoder\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Envato extends AbstractProvider
{
    /**
     * Default scopes
     *
     * @var array
     */
    public $defaultScopes = [''];

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://api.envato.com/authorization';
    }

    /**
     * Get access token url to retrieve token
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://api.envato.com/token';
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
        $user = new EnvatoResourceOwner($response);
        $email = $this->getEmail($response, $token);
        $username = $this->getUsername($response, $token);
		if($user->setEmail($email) && $user->setUsername($username))
		{
			return $user;	
		} else {
			return FALSE;	
		}
        
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
        return 'https://api.envato.com/v1/market/private/user/account.json?access_token='.$token;
    }

    /**
     * Get user email from provider
     *
     * @param  array        $response
     * @param  AccessToken  $token
     *
     * @return array
     */
    protected function getEmail(array $response, AccessToken $token)
    {
		
        $url = 'https://api.envato.com/v1/market/private/user/email.json?access_token='.$token;
        $request = $this->getAuthenticatedRequest('get', $url, $token);
        $response = $this->getResponse($request);
        return $response['email'];
		
    }
	
    /**
     * Get username from provider
     *
     * @param  array        $response
     * @param  AccessToken  $token
     *
     * @return array
     */
    protected function getUsername(array $response, AccessToken $token)
    {
        $url = 'https://api.envato.com/v1/market/private/user/username.json?access_token='.$token;
        $request = $this->getAuthenticatedRequest('get', $url, $token);
        $response = $this->getResponse($request);
        return $response['username'];
		
    }

}
