<?php

namespace Bluetea\CrowdAuthenticationBundle\Crowd;

interface UserInterface
{
    public function getUsername();
    public function setUsername($username);
    public function getFirstName();
    public function setFirstName($firstName);
    public function getLastName();
    public function setLastName($lastName);
    public function getDisplayName();
    public function setDisplayName($displayName);
    public function getEmail();
    public function setEmail($email);
    public function isActive();
    public function setActive($active);
}
?>