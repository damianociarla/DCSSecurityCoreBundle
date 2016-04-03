<?php

namespace DCS\Security\CoreBundle\Tests\Event;

use DCS\Security\CoreBundle\Event\UsernameBeforeLoadEvent;

class UsernameBeforeLoadEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UsernameBeforeLoadEvent
     */
    private $event;

    protected function setUp()
    {
        $this->event = new UsernameBeforeLoadEvent('johndoe');
    }

    public function testGetUsername()
    {
        $this->assertEquals('johndoe', $this->event->getUsername());
    }
}