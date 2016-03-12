<?php

namespace DCS\Security\CoreBundle\Authentication;

use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TokenService implements TokenServiceInterface
{
    /**
     * @var string
     */
    private $providerKey;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    function __construct($providerKey, TokenStorageInterface $tokenStorage)
    {
        $this->providerKey = $providerKey;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @inheritdoc
     */
    public function createFromUser(UserInterface $user, array $roles = [])
    {
        $userRoles = $user->getRoles();

        if (!empty($userRoles)) {
            $roles += $userRoles;
        }

        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $this->providerKey,
            $roles
        );
    }

    /**
     * @inheritdoc
     */
    public function refreshFromUser(UserInterface $user, array $roles = [])
    {
        $this->tokenStorage->setToken($this->createFromUser($user, $roles));
    }
}