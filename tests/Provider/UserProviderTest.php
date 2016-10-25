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

    /**
     * @var UserRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $userRepository;

    protected function setUp()
    {
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userProvider = new UserProvider($this->userRepository, $this->dispatcher);
    }

    public function testLoadUserByUsernameSuccessfully()
    {
        $this->userRepository->expects($this->any())
            ->method('findOneByUsername')
            ->willReturn($this->getUser());

        $this->dispatcher->expects($this->exactly(2))->method('dispatch');
        $this->dispatcher->expects($this->at(0))->method('dispatch')->with(DCSSecurityCoreEvents::PROVIDER_BEFORE_LOAD_USER, $this->isInstanceOf(UsernameBeforeLoadEvent::class));
        $this->dispatcher->expects($this->at(1))->method('dispatch')->with(DCSSecurityCoreEvents::PROVIDER_AFTER_LOAD_USER, $this->isInstanceOf(UserLoadedEvent::class));

        $this->assertInstanceOf(UserInterface::class, $this->userProvider->loadUserByUsername('johndoe'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameNonExistent()
    {
        $this->userRepository->expects($this->once())
            ->method('findOneByUsername')
            ->willReturn(null);

        $this->userProvider->loadUserByUsername('johndoe');
    }

    public function testRefreshUser()
    {
        $this->userRepository->expects($this->any())
            ->method('findOneByUsername')
            ->willReturn($this->getUser());

        $this->assertInstanceOf(UserInterface::class, $this->userProvider->refreshUser($this->createMock(UserInterface::class)));
        $this->expectException(UnsupportedUserException::class);
        $this->userProvider->refreshUser($this->createMock(\Symfony\Component\Security\Core\User\UserInterface::class));
    }

    public function testSupportsClass()
    {
        $this->assertTrue($this->userProvider->supportsClass($this->createMock(User::class)));
        $this->assertFalse($this->userProvider->supportsClass($this->createMock(\Symfony\Component\Security\Core\User\UserInterface::class)));
    }

    /**
     * @return UserInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUser()
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getUsername')
            ->willReturn('johndoe');
        return $user;
    }
}