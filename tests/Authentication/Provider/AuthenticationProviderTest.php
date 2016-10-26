<?php

namespace DCS\Security\CoreBundle\Tests\Authentication\Provider;

use DCS\Security\CoreBundle\Authentication\Provider\AuthenticationProvider;
use DCS\Security\CoreBundle\DCSSecurityCoreEvents;
use DCS\Security\CoreBundle\Event\AuthenticatedTokenEvent;
use DCS\Security\CoreBundle\Provider\UserProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AuthenticationProvider
     */
    private $authenticationProvider;

    /**
     * @var EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dispatcher;

    /**
     * @var string
     */
    private $key;

    protected function setUp()
    {
        $userProvider = $this->createMock(UserProviderInterface::class);
        $userChecker = $this->createMock(UserCheckerInterface::class);
        $this->key = 'key';
        $encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);

        $this->authenticationProvider = new AuthenticationProvider($userProvider, $userChecker, $this->key, $encoderFactory, $this->dispatcher, true);
    }

    public function testAuthenticate()
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getRoles')
            ->willReturn([]);
        $token = $this->createMock(UsernamePasswordToken::class);
        $token->method('getProviderKey')
            ->willReturn($this->key);
        $token->method('getUser')
            ->willReturn($user);
        $token->method('getRoles')
            ->willReturn([]);
        $token->method('getAttributes')
            ->willReturn([]);

        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(DCSSecurityCoreEvents::AUTHENTICATED_USER), $this->isInstanceOf(AuthenticatedTokenEvent::class));

        $this->assertInstanceOf(TokenInterface::class, $this->authenticationProvider->authenticate($token));
    }
}