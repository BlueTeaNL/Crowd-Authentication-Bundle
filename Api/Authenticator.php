<?php

namespace Bluetea\CrowdAuthenticationBundle\Api;

use Bluetea\CrowdAuthenticationBundle\Crowd\UserMapper;
use Bluetea\CrowdAuthenticationBundle\Exception\UserNotFoundException;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

class Authenticator
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var \Bluetea\CrowdAuthenticationBundle\Crowd\UserMapper
     */
    private $userMapper;

    /**
     * @param Client $httpClient
     * @param UserMapper $userMapper
     */
    public function __construct(Client $httpClient, UserMapper $userMapper)
    {
        $this->httpClient = $httpClient;
        $this->userMapper = $userMapper;
    }

    /**
     * Authenticate with CROWD
     *
     * @param $username
     * @param $password
     * @return bool
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function authenticateWithCrowd($username, $password)
    {
        $request = $this->httpClient->post(
            sprintf('authentication?username=%s', urlencode($username)),
            null,
            json_encode(array('value' => $password))
        );

        try {
            $request->send();
        } catch (ClientErrorResponseException $exception) {
            if (400 === $exception->getResponse()->getStatusCode()) {
                return false;
            }
            throw $exception;
        }

        return true;
    }

    /**
     * Retrieve the user from CROWD by it's username
     *
     * @param $username
     * @return mixed
     * @throws \Exception
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException
     * @throws \Bluetea\CrowdAuthenticationBundle\Exception\UserNotFoundException
     */
    public function getUserByUsername($username)
    {
        $request = $this->httpClient->get('user?username=' . urlencode($username) . '&expand=attributes');

        try {
            $response = $request->send();
        } catch (ClientErrorResponseException $exception) {
            if (404 === $exception->getResponse()->getStatusCode()) {
                throw new UserNotFoundException($exception->getResponse()->getBody());
            }
            throw $exception;
        }
        $userMapper = $this->userMapper;
        return $userMapper($response->json());
    }
} 