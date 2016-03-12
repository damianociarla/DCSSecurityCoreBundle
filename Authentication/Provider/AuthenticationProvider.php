<?php

namespace DCS\Security\CoreBundle\Authentication\Provider;

use DCS\Security\CoreBundle\DCSSecurityCoreEvents;
use DCS\Security\CoreBundle\Event\AuthenticatedTokenEvent;
use DCS\Security\CoreBundle\Provider\UserProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class AuthenticationProvider extends DaoAuthenticationProvider
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        $providerKey,
        EncoderFactoryInterface $encoderFactory,
        EventDispatcherInterface $eventDispatcher,
        $hideUserNotFoundExceptions
    ) {
        parent::__construct($userProvider, $userChecker, $providerKey, $encoderFactory, $hideUserNotFoundExceptions);
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(TokenInterface $token)
    {
        $event = new AuthenticatedTokenEvent(parent::authenticate($token));

        $this->eventDispatcher->dispatch(DCSSecurityCoreEvents::AUTHENTICATED_USER, $event);

        return $event->getToken();
    }
}