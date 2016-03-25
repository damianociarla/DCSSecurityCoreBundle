<?php

namespace DCS\Security\CoreBundle\Event;

use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\Event;

class UserLoadedEvent extends Event
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var string
     */
    private $username;

    public function __construct(UserInterface $user, $username)
    {
        $this->user = $user;
        $this->username = $username;
    }

    /**
     * Get user
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}