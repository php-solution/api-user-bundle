<?php

namespace PhpSolution\ApiUserBundle\Process;

use Doctrine\ORM\NoResultException;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Form\Type\ForgotPasswordFormType;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
     * @param array $data
     *
     * @return FormInterface|UserInterface
     * @throws NoResultException
     */
    public function createToken(array $data)
    {
        $form = $this->formFactory->create(ForgotPasswordFormType::class);
        $form->submit($data);
        if (!$form->isValid()) {
            return $form;
        }
        $user = $this->userService->getRepository()->findOneByEmailAndEnabled($form->getData()['email'], true);
        if (null === $user) {
            throw new NoResultException();
        }

        $user->setConfirmationToken($this->tokenGenerator->generateToken());

        $this->userService->updateUser($user);
        $this->eventDispatcher->dispatch(UserEvents::FORGOT_PASSWORD_TOKEN_CREATED, new UserEvent($user));

        return $user;
    }

    /**
     * @param string $token
     * @param array  $data
     *
     * @return FormInterface|UserInterface
     * @throws NoResultException
     */
    public function resetPassword(string $token, array $data)
    {
        $user = $this->userService->getRepository()->findOneByConfirmationToken($token);
        if ($user === null) {
            throw new NoResultException();
        }

        $form = $this->formFactory->create($this->resetPasswordFormClass, $user);
        $form->submit($data);
        if (!$form->isValid()) {
            return $form;
        }

        $user->setConfirmationToken(null);
        $this->userService->updateUser($user);
        $this->eventDispatcher->dispatch(UserEvents::FORGOT_PASSWORD_COMPLETED, new UserEvent($user));

        return $user;
    }
}