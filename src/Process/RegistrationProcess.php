<?php

namespace PhpSolution\ApiUserBundle\Process;

use Doctrine\ORM\NoResultException;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use PhpSolution\ApiUserBundle\Util\UserFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * RegistrationProcess
 */
class RegistrationProcess extends AbstractProcess
{
    /**
     * @var UserFactory
     */
    protected $userFactory;
    /**
     * @var string
     */
    protected $registrationFormClass;

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
        $user = $this->getUser($data);
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
     * @param array $data
     *
     * @return UserInterface
     */
    protected function getUser(array $data): UserInterface
    {
        if (array_key_exists('email', $data)) {
            $user = $this->userService->getRepository()->findOneByEmailAndEnabled($data['email'], false);
        }

        return isset($user) ? $user : $this->userFactory->createUser();
    }

    /**
     * @param string $token
     *
     * @return UserInterface
     * @throws NoResultException
     */
    public function confirm(string $token): UserInterface
    {
        $user = $this->userService->getRepository()->findOneByConfirmationToken($token);
        if (null === $user || $user->isEnabled()) {
            throw new NoResultException();
        }

        $user
            ->setConfirmationToken(null)
            ->setEnabled(true);
        $this->userService->updateUser($user);

        $this->eventDispatcher->dispatch(UserEvents::REGISTRATION_CONFIRMED, new UserEvent($user));

        return $user;
    }
}