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
     * @var object
     */
    private $dto;

    /**
     * @param UserInterface $user
     * @param null|object   $dto
     */
    public function __construct(UserInterface $user, $dto = null)
    {
        $this->user = $user;
        $this->dto = $dto;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @return object
     */
    public function getDto()
    {
        return $this->dto;
    }
}
