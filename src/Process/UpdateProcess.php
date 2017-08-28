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
 * UpdateProcess
 */
class UpdateProcess extends AbstractProcess
{
    /**
     * @var string
     */
    private $updateFormClass;

    /**
     * @param FormFactory              $formFactory
     * @param UserService              $userService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FormFactory $formFactory, UserService $userService, EventDispatcherInterface $eventDispatcher, string $updateFormClass)
    {
        parent::__construct($formFactory, $userService, $eventDispatcher);
        $this->updateFormClass = $updateFormClass;
    }

    /**
     * @param UserInterface $user
     * @param array         $data
     *
     * @return FormInterface|UserInterface
     */
    public function update(UserInterface $user, array $data)
    {
        $form = $this->formFactory->create($this->updateFormClass, $user);
        $form->submit($data);
        if (!$form->isValid()) {
            return $form;
        }

        $this->userService->updateUser($user);
        $this->eventDispatcher->dispatch(UserEvents::UPDATE_COMPLETED, new UserEvent($user));

        return $user;
    }
}