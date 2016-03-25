<?php

namespace DCS\Security\CoreBundle\Authentication;

use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

interface TokenServiceInterface
{
    /**
     * Create a new token from User
     *
     * @param UserInterface $user
     * @param array $roles
     *
     * @return UsernamePasswordToken
     * @throws \InvalidArgumentException
     */
    public function createFromUser(UserInterface $user, array $roles = []);

    /**
     * Sets and creates a new token
     *
     * @param UserInterface $user
     * @param array $roles
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function refreshFromUser(UserInterface $user, array $roles = []);
}