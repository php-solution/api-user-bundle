<?php

namespace PhpSolution\ApiUserBundle\Process;

use PhpSolution\ApiUserBundle\Dto\UpdateDto;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * UpdateProcess
 */
class UpdateProcess
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
     * @param UserService              $userService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserService $userService, EventDispatcherInterface $eventDispatcher)
    {
        $this->userService = $userService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param UserInterface $user
     * @param UpdateDto     $dto
     *
     * @return ConstraintViolationListInterface|UserInterface
     */
    public function update(UserInterface $user, UpdateDto $dto)
    {
        $result = $this->userService->updateUser($user, $dto);
        if ($result instanceof ConstraintViolationListInterface) {
            return $result;
        }

        $this->eventDispatcher->dispatch(UserEvents::UPDATE_COMPLETED, new UserEvent($user));

        return $user;
    }
}