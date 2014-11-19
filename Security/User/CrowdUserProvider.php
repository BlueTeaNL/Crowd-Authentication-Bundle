<?php

namespace Bluetea\CrowdAuthenticationBundle\Security\User;

use Bluetea\CrowdAuthenticationBundle\Api\Authenticator;
use Bluetea\CrowdAuthenticationBundle\Exception\UserNotFoundException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CrowdUserProvider implements UserProviderInterface
{
    /**
     * @var Authenticator
     */
    protected $authenticator;

    /**
     * @var Session
     */
    protected $session;

    public function __construct(Authenticator $authenticator, Session $session)
    {
        $this->authenticator = $authenticator;
        $this->session = $session;
    }

    /**
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        $sessionKey = 'bluetea_crowd_authentication.user';
        // Check if the user isn't available yet
        if ($this->session->has($sessionKey)) {
            return $this->session->get($sessionKey);
        }
        // Find the user
        $user = $this->findUser($username);
        // Set the session
        $this->session->set($sessionKey, $user);
        // Return the user
        return $user;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException();
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class == 'Bluetea\CrowdAuthenticationBundle\Security\User\User';
    }

    /**
     * Find user by username
     *
     * @param $username
     * @return User
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    protected function findUser($username)
    {
        try {
            $user = User::fromUser($this->authenticator->getUserByUsername($username));
        } catch (UserNotFoundException $e) {
            throw new UsernameNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
        return $user;
    }
}