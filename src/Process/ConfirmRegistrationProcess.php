<?php

namespace PhpSolution\ApiUserBundle\Process;

use PhpSolution\StdLib\Exception\NotFoundException;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ConfirmRegistrationProcess
 */
class ConfirmRegistrationProcess
{
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param UserService              $userClass
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserService $userClass, EventDispatcherInterface $eventDispatcher)
    {
        $this->userService = $userClass;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $token
     *
     * @return UserInterface
     */
    public function confirm(string $token): UserInterface
    {
        $user = $this->userService->getRepository()->findOneByConfirmationToken($token);
        if ($user === null) {
            throw new NotFoundException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user
            ->setConfirmationToken(null)
            ->setEnabled(true);
        $this->userService->updateUser($user);

        $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_CONFIRMED, new UserEvent($user));

        return $user;
    }
}