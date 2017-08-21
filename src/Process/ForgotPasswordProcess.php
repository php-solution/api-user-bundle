<?php

namespace PhpSolution\ApiUserBundle\Process;

use PhpSolution\StdLib\Exception\NotFoundException;
use PhpSolution\ApiUserBundle\Dto\ResetPasswordDto;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * ForgotPasswordProcess
 */
class ForgotPasswordProcess
{
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var int
     */
    private $tokenTtl = 3600;

    /**
     * @param UserService              $userService
     * @param TokenGeneratorInterface  $tokenGenerator
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserService $userService, TokenGeneratorInterface $tokenGenerator,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->userService = $userService;
        $this->tokenGenerator = $tokenGenerator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return int
     */
    public function getTokenTtl(): int
    {
        return $this->tokenTtl;
    }

    /**
     * @param int $tokenTtl
     *
     * @return self
     */
    public function setTokenTtl(int $tokenTtl)
    {
        $this->tokenTtl = $tokenTtl;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return ConstraintViolationListInterface|UserInterface
     */
    public function createToken(string $email)
    {
        $user = $this->userService->getRepository()->findOneEnabledByEmail($email);
        if ($user === null) {
            throw new \RuntimeException(sprintf('The user with email "%s" does not exist', $email));
        }
        if ($user->isPasswordRequestNonExpired($this->getTokenTtl())) {
            $message = 'The password for this user has already been requested';

            return new ConstraintViolationList([new ConstraintViolation($message, $message, [], null, null, null)]);
        }

        $user
            ->setConfirmationToken($this->tokenGenerator->generateToken())
            ->setPasswordRequestedAt(new \DateTime());

        $this->userService->updateUser($user);

        $this->eventDispatcher->dispatch(UserEvents::FORGOT_PASSWORD_TOKEN_CREATED, new UserEvent($user));

        return $user;
    }

    /**
     * @param ResetPasswordDto $dto
     *
     * @return ConstraintViolationListInterface|UserInterface
     */
    public function resetPassword(ResetPasswordDto $dto)
    {
        $token = $dto->getToken();
        $user = $this->userService->getRepository()->findOneByConfirmationToken($token);
        if ($user === null) {
            throw new NotFoundException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $result = $this->userService->updateUser($user, $dto, ['resetPassword']);
        if ($result instanceof ConstraintViolationListInterface) {
            return $result;
        }

        $this->eventDispatcher->dispatch(UserEvents::FORGOT_PASSWORD_COMPLETED, new UserEvent($user));

        return $user;
    }
}