<?php

namespace DCS\Security\CoreBundle\Tests\Authentication;

use DCS\Security\CoreBundle\Authentication\TokenService;
use DCS\Security\CoreBundle\Tests\TestUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TokenServiceTest extends \PHPUnit_Framework_TestCase
{
    const PROVIDER_KEY = 'default';

    /**
     * @var TokenService
     */
    private $tokenService;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $tokenStorage;

    protected function setUp()
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->tokenService = new TokenService(self::PROVIDER_KEY, $this->tokenStorage);
    }

    public function testCreateFromUser()
    {
        $user = $this->getUser();
        $token = $this->tokenService->createFromUser($user);

        $this->assertInstanceOf(UsernamePasswordToken::class, $token);
        $this->assertEquals($user, $token->getUser());
        $this->assertEquals('johndoe', $token->getUsername());
        $this->assertEquals(self::PROVIDER_KEY, $token->getProviderKey());
        $this->assertCount(1, $token->getRoles());
    }

    public function testRefreshFromUser()
    {
        $this->tokenStorage->expects($this->exactly(1))->method('setToken')->with($this->isInstanceOf(UsernamePasswordToken::class));
        $this->tokenService->refreshFromUser($this->getUser());
    }

    /**
     * @return TestUser
     */
    private function getUser()
    {
        $user = new TestUser();
        $user->setUsername('johndoe');

        return $user;
    }
}