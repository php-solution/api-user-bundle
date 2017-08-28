<?php

namespace PhpSolution\ApiUserBundle\Event;

use PhpSolution\ApiUserBundle\Entity\UserInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * UserEvent
 */
class UserEvent extends Event
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
