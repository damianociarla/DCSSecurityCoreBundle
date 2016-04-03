<?php

namespace DCS\Security\CoreBundle\Tests\Event;

use DCS\Security\CoreBundle\Event\UserLoadedEvent;
use DCS\User\CoreBundle\Model\UserInterface;

class UserLoadedEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserLoadedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->event = new UserLoadedEvent($this->getMock(UserInterface::class), 'johndoe');
    }

    public function testGetUser()
    {
        $this->assertInstanceOf(UserInterface::class, $this->event->getUser());
    }

    public function testGetUsername()
    {
        $this->assertEquals('johndoe', $this->event->getUsername());
    }
}