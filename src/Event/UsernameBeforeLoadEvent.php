<?php

namespace DCS\Security\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class UsernameBeforeLoadEvent extends Event
{
    /**
     * @var string
     */
    private $username;

    public function __construct($username)
    {
        $this->username = $username;
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