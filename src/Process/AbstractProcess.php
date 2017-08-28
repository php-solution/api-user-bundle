<?php

namespace PhpSolution\ApiUserBundle\Process;

use PhpSolution\ApiUserBundle\Service\UserService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactory;

/**
 * AbstractProcess
 */
class AbstractProcess
{
    /**
     * @var FormFactory
     */
    protected $formFactory;
    /**
     * @var UserService
     */
    protected $userService;
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param FormFactory              $formFactory
     * @param UserService              $userService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FormFactory $formFactory, UserService $userService, EventDispatcherInterface $eventDispatcher)
    {
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->eventDispatcher = $eventDispatcher;
    }
}