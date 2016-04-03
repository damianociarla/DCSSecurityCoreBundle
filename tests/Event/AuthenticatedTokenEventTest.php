<?php

namespace DCS\Security\CoreBundle\Tests\Event;

use DCS\Security\CoreBundle\Event\AuthenticatedTokenEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticatedTokenEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AuthenticatedTokenEvent
     */
    private $authenticatedTokenEvent;

    public function setUp()
    {
        $this->authenticatedTokenEvent = new AuthenticatedTokenEvent(
            new UsernamePasswordToken('johndoe', 'passwd', 'provider', ['ROLE_DEFAULT'])
        );
    }

    public function testSetterAndGetter()
    {
        $this->assertInstanceOf(UsernamePasswordToken::class, $this->authenticatedTokenEvent->getToken());
        $this->assertEquals('johndoe', $this->authenticatedTokenEvent->getToken()->getUser());

        $this->authenticatedTokenEvent->setToken(
            new UsernamePasswordToken('einstein', 'e=mc2', 'provider', ['ROLE_SCIENTIST'])
        );

        $this->assertCount(1, $this->authenticatedTokenEvent->getToken()->getRoles());
        $this->assertEquals('einstein', $this->authenticatedTokenEvent->getToken()->getUser());
    }

    public function testRegenerateUsernamePasswordTokenWithRoles()
    {
        $this->authenticatedTokenEvent->regenerateUsernamePasswordTokenWithRoles(['ROLE_NEW'], false);
        $this->assertCount(1, $this->authenticatedTokenEvent->getToken()->getRoles());
        
        $this->authenticatedTokenEvent->regenerateUsernamePasswordTokenWithRoles(['ROLE_NEW_NEW']);
        $this->assertCount(2, $this->authenticatedTokenEvent->getToken()->getRoles());
    }
}