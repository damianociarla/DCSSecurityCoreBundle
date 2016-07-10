<?php

namespace DCS\Security\CoreBundle\Provider;

use DCS\Security\CoreBundle\DCSSecurityCoreEvents;
use DCS\Security\CoreBundle\Event\UserLoadedEvent;
use DCS\Security\CoreBundle\Event\UsernameBeforeLoadEvent;
use DCS\User\CoreBundle\Model\User;
use DCS\User\CoreBundle\Model\UserInterface as DCSUserInterface;
use DCS\User\CoreBundle\Repository\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(UserRepositoryInterface $userRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function loadUserByUsername($username)
    {
        $this->eventDispatcher->dispatch(
            DCSSecurityCoreEvents::PROVIDER_BEFORE_LOAD_USER,
            new UsernameBeforeLoadEvent($username)
        );

        $user = $this->findUser($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        $this->eventDispatcher->dispatch(
            DCSSecurityCoreEvents::PROVIDER_AFTER_LOAD_USER,
            new UserLoadedEvent($user, $username)
        );

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof DCSUserInterface) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return is_subclass_of($class, User::class);
    }

    /**
     * Finds a user by username.
     * This method is meant to be an extension point for child classes.
     *
     * @param string $username
     * @return DCSUserInterface|null
     */
    protected function findUser($username)
    {
        return $this->userRepository->findOneByUsername($username);
    }
}