<?php

namespace Bluetea\CrowdAuthenticationBundle\Crowd;

class UserMapper
{
    /**
     * @param $user
     * @return array|UserInterface
     */
    public function __invoke($user)
    {
        if ($user instanceof UserInterface) {
            return $this->mapUserToRestRepresentation($user);
        }
        return $this->mapRestRepresentationToUser($user);
    }

    /**
     * @param UserInterface $user
     *
     * @return array
     */
    private function mapUserToRestRepresentation(UserInterface $user)
    {
        return array(
            'first-name' => $user->getFirstName(),
            'last-name' => $user->getLastName(),
            'display-name' => $user->getDisplayName(),
            'email' => $user->getEmail(),
            'active' => $user->isActive(),
            'name' => $user->getUsername()
        );
    }

    /**
     * @param string $data
     * @return UserInterface $user
     */
    private function mapRestRepresentationToUser($data)
    {
        $user = new User();

        $user->setFirstName($data['first-name']);
        $user->setLastName($data['last-name']);
        $user->setDisplayName($data['display-name']);
        $user->setEmail($data['email']);
        $user->setActive($data['active']);
        $user->setUsername($data['name']);

        return $user;
    }
}