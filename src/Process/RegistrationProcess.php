<?php

namespace PhpSolution\ApiUserBundle\Process;

use PhpSolution\ApiUserBundle\Dto\RegistrationDto;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use PhpSolution\ApiUserBundle\Util\UserFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * RegistrationProcess
 */
class RegistrationProcess
{
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param UserFactory              $userFactory
     * @param UserService              $userService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserFactory $userFactory, UserService $userService, EventDispatcherInterface $eventDispatcher)
    {
        $this->userFactory = $userFactory;
        $this->userService = $userService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param RegistrationDto $dto
     *
     * @return ConstraintViolationListInterface|UserInterface
     */
    public function register(RegistrationDto $dto)
    {
        $user = $this->userFactory->createUser();
        $result = $this->userService->updateUser($user, $dto, ['registration']);
        if ($result instanceof ConstraintViolationListInterface) {
            return $result;
        }

        $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_COMPLETED, new UserEvent($user, $dto));

        return $user;
    }
}