<?php namespace Firstcoder\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class YandexResourceOwner implements ResourceOwnerInterface
{

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->response['id'] ?: null;
    }

    /**
     * Get resource owner email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->response['default_email'] ?: null;
    }

    /**
     * Get resource owner name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->response['real_name'] ?: null;
    }

    /**
     * Get resource owner nickname
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->response['login'] ?: null;
    }

    /**
     * Get resource owner nickname
     *
     * @return string|null
     */
    public function getNickname()
    {
        return $this->response['display_name'] ?: null;
    }
	
    /**
     * Get resource owner First name
     *
     * @return string|null
     */
    public function getFirstname()
    {
        return $this->response['first_name'] ?: null;
    }
	
    /**
     * Get resource owner Last name
     *
     * @return string|null
     */
    public function getLastname()
    {
        return $this->response['last_name'] ?: null;
    }



    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
