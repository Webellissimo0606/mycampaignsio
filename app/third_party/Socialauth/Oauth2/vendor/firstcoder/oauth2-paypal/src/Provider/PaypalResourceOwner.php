<?php namespace Firstcoder\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class PaypalResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

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
     * Get user id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->response['user_id'] ?: null;
    }


    /**
     * Get user email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->response['email'] ?: null;
    }


    /**
     * Get user name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->response['name'] ?: null;
    }
	
    /**
     * Get user first name
     *
     * @return string|null
     */
    public function getFirstname()
    {
        return $this->response['given_name'] ?: null;
    }
	
    /**
     * Get user name
     *
     * @return string|null
     */
    public function getLastname()
    {
        return $this->response['family_name'] ?: null;
    }


    /**
     * Get user name
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->response['address']['country'] ?: null;
    }
	
    /**
     * Get user name
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->response['address'] ?: null;
    }

    /**
     * Get user urls
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->response['phone_number'] ?: null;
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
