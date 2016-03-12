<?php

namespace DCS\Security\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticatedTokenEvent extends Event
{
    /**
     * @var TokenInterface
     */
    private $token;

    public function __construct(UsernamePasswordToken $token)
    {
        $this->token = $token;
    }

    /**
     * Get token
     *
     * @return TokenInterface
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets token
     *
     * @param TokenInterface $token
     */
    public function setToken(TokenInterface $token)
    {
        $this->token = $token;
    }

    /**
     * Regenerate same instance of this token with aother roles
     *
     * @param array $roles
     * @param bool $mergeWithExistingRoles
     */
    public function regenerateUsernamePasswordTokenWithRoles(array $roles, $mergeWithExistingRoles = true)
    {
        $user = $this->token->getUser();
        $credentials = $this->token->getCredentials();
        $providerKey = $this->token->getProviderKey();

        if ($mergeWithExistingRoles) {
            $roles = array_merge($roles, $this->token->getRoles());
        }

        $this->token = new UsernamePasswordToken($user, $credentials, $providerKey, $roles);
    }
}