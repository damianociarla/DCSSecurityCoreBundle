<?php

namespace DCS\Security\CoreBundle\Tests\Provider;

use DCS\Security\CoreBundle\DCSSecurityCoreEvents;
use DCS\Security\CoreBundle\Event\UserLoadedEvent;
use DCS\Security\CoreBundle\Event\UsernameBeforeLoadEvent;
use DCS\Security\CoreBundle\Provider\UserProvider;
use DCS\User\CoreBundle\Model\User;
use DCS\User\CoreBundle\Model\UserInterface;
use DCS\User\CoreBundle\Repository\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var UserProvider
     */
    private $userProvider;

    protected function setUp()
    {
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);

        $user = $this->createMock(UserInterface::class);
        $user->method('getUsername')->willReturn('johndoe');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->any())
            ->method('findOneByUsername')
            ->willReturn($user);

        $this->userProvider = new UserProvider($userRepository, $this->dispatcher);
    }

    public function testLoadUserByUsernameSuccessfully()
    {
        $this->dispatcher->expects($this->exactly(2))->method('dispatch');
        $this->dispatcher->expects($this->at(0))->method('dispatch')->with(DCSSecurityCoreEvents::PROVIDER_BEFORE_LOAD_USER, $this->isInstanceOf(UsernameBeforeLoadEvent::class));
        $this->dispatcher->expects($this->at(1))->method('dispatch')->with(DCSSecurityCoreEvents::PROVIDER_AFTER_LOAD_USER, $this->isInstanceOf(UserLoadedEvent::class));

        $this->assertInstanceOf(UserInterface::class, $this->userProvider->loadUserByUsername('johndoe'));
    }

    public function testRefreshUser()
    {
        $this->assertInstanceOf(UserInterface::class, $this->userProvider->refreshUser($this->createMock(UserInterface::class)));
        $this->expectException(UnsupportedUserException::class);
        $this->userProvider->refreshUser($this->createMock(\Symfony\Component\Security\Core\User\UserInterface::class));
    }

    public function testSupportsClass()
    {
        $this->assertTrue($this->userProvider->supportsClass($this->createMock(User::class)));
        $this->assertFalse($this->userProvider->supportsClass($this->createMock(\Symfony\Component\Security\Core\User\UserInterface::class)));
    }
}