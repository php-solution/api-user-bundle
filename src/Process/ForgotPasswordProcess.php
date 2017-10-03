<?php

namespace PhpSolution\ApiUserBundle\Process;

use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * ForgotPasswordProcess
 */
class ForgotPasswordProcess extends AbstractProcess
{
    /**
     * @var string
     */
    protected $resetPasswordFormClass;
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;
    /**
     * @var int
     */
    private $tokenTtl = 3600;

    /**
     * @param FormFactory              $formFactory
     * @param UserService              $userService
     * @param EventDispatcherInterface $eventDispatcher
     * @param TokenGeneratorInterface  $tokenGenerator
     * @param string                   $resetPasswordFormClass
     */
    public function __construct(FormFactory $formFactory, UserService $userService, EventDispatcherInterface $eventDispatcher,
                                TokenGeneratorInterface $tokenGenerator, string $resetPasswordFormClass)
    {
        parent::__construct($formFactory, $userService, $eventDispatcher);
        $this->tokenGenerator = $tokenGenerator;
        $this->resetPasswordFormClass = $resetPasswordFormClass;
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
        $user = $this->userService->getRepository()->findOneByEmailAndEnabled($email, true);
        if ($user === null) {
            $message = sprintf('The user with email "%s" does not exist', $email);

            return new ConstraintViolationList([new ConstraintViolation($message, $message, [], null, 'email', $email)]);
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
     * @param string $token
     * @param array  $data
     *
     * @return FormInterface|ConstraintViolationListInterface|UserInterface
     */
    public function resetPassword(string $token, array $data)
    {
        $user = $this->userService->getRepository()->findOneByConfirmationToken($token);
        if ($user === null) {
            $message = sprintf('The user with confirmation token "%s" does not exist', $token);

            return new ConstraintViolationList([new ConstraintViolation($message, $message, [], null, 'token', $token)]);
        }

        $form = $this->formFactory->create($this->resetPasswordFormClass, $user);
        $form->submit($data);
        if (!$form->isValid()) {
            return $form;
        }

        $this->eventDispatcher->dispatch(UserEvents::FORGOT_PASSWORD_COMPLETED, new UserEvent($user));

        return $user;
    }
}