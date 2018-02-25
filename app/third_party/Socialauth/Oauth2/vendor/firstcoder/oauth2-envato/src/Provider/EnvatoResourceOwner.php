<?php namespace Firstcoder\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class EnvatoResourceOwner implements ResourceOwnerInterface
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
     * Image url
     *
     * @var string
     */
    protected $email;
	
    protected $username;

    /**
     * Get user id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->username;
    }


    /**
     * Get user email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user email
     *
     * @return string|null
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get user email
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set user email
     *
     * @return string|null
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get user firstname
     *
     * @return string|null
     */
    public function getFirstname()
    {
        return $this->response['account']['firstname'] ?: null;
    }

    /**
     * Get user lastname
     *
     * @return string|null
     */
    public function getLastname()
    {
        return $this->response['account']['surname'] ?: null;
    }

    /**
     * Get user name
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->response['account']['country'] ?: null;
    }
	
    /**
     * Get user name
     *
     * @return string|null
     */
    public function getImage()
    {
        return $this->response['account']['image'] ?: null;
    }

    /**
     * Get user urls
     *
     * @return string|null
     */
    public function getLink()
    {
		if(isset($this->username)) {
			$url = 'http://codecanyon.net/user/'.$this->username;
		} else {
			$url = 'http://codecanyon.net/';
		}
		return $url;

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
