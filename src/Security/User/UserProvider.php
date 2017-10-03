<?php

namespace PhpSolution\ApiUserBundle\Security\User;

use Doctrine\Bundle\DoctrineBundle\Registry;
use PhpSolution\ApiUserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * UserProvider
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var string
     */
    private $userClass;
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @param string   $userClass
     * @param Registry $doctrine
     */
    public function __construct(string $userClass, Registry $doctrine)
    {
        $this->userClass = $userClass;
        $this->repository = $doctrine->getRepository($userClass);
    }

    /**
     * @param string $username
     *
     * @return UserInterface
     */
    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->repository->findOneByEmail($username);
        if ($user instanceof $this->userClass) {
            if (!$user->isEnabled()) {
                throw new UserNotEnabledException(sprintf('Username "%s" does not enabled.', $username));
            }
            return $user;
        }

        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return $this->userClass === $class;
    }
}