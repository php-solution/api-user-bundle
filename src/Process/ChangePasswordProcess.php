<?php

namespace PhpSolution\ApiUserBundle\Process;

use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\Service\UserService;
use PhpSolution\ApiUserBundle\UserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * ChangePasswordProcess
 */
class ChangePasswordProcess extends AbstractProcess
{
    /**
     * @var string
     */
    protected $changePasswordFormClass;

    /**
     * @param FormFactory              $formFactory
     * @param UserService              $userService
     * @param EventDispatcherInterface $eventDispatcher
     * @param string                   $changePasswordFormClass
     */
    public function __construct(FormFactory $formFactory, UserService $userService,
                                EventDispatcherInterface $eventDispatcher, string $changePasswordFormClass)
    {
        parent::__construct($formFactory, $userService, $eventDispatcher);
        $this->changePasswordFormClass = $changePasswordFormClass;
    }

    /**
     * @param UserInterface     $user
     * @param array             $data
     *
     * @return FormInterface|UserInterface
     */
    public function change(UserInterface $user, array $data)
    {
        $form = $this->formFactory->create($this->changePasswordFormClass, $user);
        $form->submit($data);
        if (!$form->isValid()) {
            return $form;
        }

        $this->userService->updateUser($user);
        $this->eventDispatcher->dispatch(UserEvents::CHANGE_PASSWORD_COMPLETED, new UserEvent($user));

        return $user;
    }
}