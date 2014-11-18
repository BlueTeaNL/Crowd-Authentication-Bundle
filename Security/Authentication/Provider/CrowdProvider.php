<?php

namespace Bluetea\CrowdAuthenticationBundle\Security\Authentication\Provider;

use Bluetea\CrowdAuthenticationBundle\Api\Authenticator;
use Bluetea\CrowdAuthenticationBundle\Security\Authentication\Token\CrowdToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CrowdProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var Authenticator
     */
    private $authenticator;

    /**
     * @param UserProviderInterface $userProvider
     * @param Authenticator $authenticator
     */
    public function __construct(UserProviderInterface $userProvider, Authenticator $authenticator)
    {
        $this->userProvider = $userProvider;
        $this->authenticator = $authenticator;
    }

    /**
     * @param TokenInterface $token
     * @return CrowdToken|TokenInterface
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        if (
            $user && (
                $token->isAuthenticated()
                ||
                $this->authenticator->authenticateWithCrowd($token->getUsername(), $token->getCredentials())
            )
        ) {
            $authenticatedToken = new CrowdToken($user->getRoles());
            $authenticatedToken->setUser($user);
            return $authenticatedToken;
        }
        throw new AuthenticationException('The Crowd authentication failed.');
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return Boolean true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof UsernamePasswordToken || $token instanceof CrowdToken;
    }


}