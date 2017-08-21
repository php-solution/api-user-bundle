<?php

namespace PhpSolution\ApiUserBundle\Process;

use PhpSolution\ApiUserBundle\Dto\ChangePasswordDto;
use PhpSolution\ApiUserBundle\Dto\NewPasswordDto;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * ChangePasswordProcess
 */
class ChangePasswordProcess
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
     * @param UserInterface     $user
     * @param ChangePasswordDto $dto
     *
     * @return ConstraintViolationListInterface|UserInterface
     */
    public function change(UserInterface $user, ChangePasswordDto $dto)
    {
        $violations = new ConstraintViolationList();
        if ($this->userService->encodePassword($dto->getOldPassword(), $user) !== $user->getPassword()) {
            $message = 'Old password is wrong';
            $violations->add(new ConstraintViolation($message, $message, [], null, 'oldPassword', null));
        }
        if ($dto->getNewPassword() !== $dto->getNewPasswordConfirmation()) {
            $message = 'New passwords do not match';
            $violations->add(new ConstraintViolation($message, $message, [], null, 'newPassword', null));
        }
        if ($violations->count() > 0) {
            return $violations;
        }

        $result = $this->userService->updateUser($user, new NewPasswordDto($dto->getNewPassword()), ['changePassword']);
        if ($result instanceof ConstraintViolationListInterface) {
            return $result;
        }

        $this->eventDispatcher->dispatch(UserEvents::CHANGE_PASSWORD_COMPLETED, new UserEvent($user, $dto));

        return $user;
    }
}