<?php

namespace PhpSolution\ApiUserBundle\Process;

use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use PhpSolution\ApiUserBundle\Util\UserFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * RegistrationProcess
 */
class RegistrationProcess extends AbstractProcess
{
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var string
     */
    private $registrationFormClass;

    /**
     * @param FormFactory              $formFactory
     * @param UserService              $userService
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserFactory              $userFactory
     * @param string                   $registrationFormClass
     */
    public function __construct(FormFactory $formFactory, UserService $userService, EventDispatcherInterface $eventDispatcher, UserFactory $userFactory, string $registrationFormClass)
    {
        parent::__construct($formFactory, $userService, $eventDispatcher);
        $this->userFactory = $userFactory;
        $this->registrationFormClass = $registrationFormClass;
    }

    /**
     * @param array $data
     *
     * @return FormInterface|UserInterface
     */
    public function register(array $data)
    {
        $user = $this->userFactory->createUser();
        $form = $this->formFactory->create($this->registrationFormClass, $user);
        $form->submit($data);
        if (!$form->isValid()) {
            return $form;
        }

        $this->userService->updateUser($user);
        $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_COMPLETED, new UserEvent($user));

        return $user;
    }

    /**
     * @param string $token
     *
     * @return ConstraintViolationListInterface|UserInterface
     */
    public function confirm(string $token)
    {
        $user = $this->userService->getRepository()->findOneByConfirmationToken($token);
        if ($user === null) {
            $message = sprintf('The user with confirmation token "%s" does not exist', $token);

            return new ConstraintViolationList([new ConstraintViolation($message, $message, [], null, 'token', $token)]);
        }

        $user
            ->setConfirmationToken(null)
            ->setEnabled(true);
        $this->userService->updateUser($user);

        $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_CONFIRMED, new UserEvent($user));

        return $user;
    }
}