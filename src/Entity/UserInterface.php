<?php

namespace PhpSolution\ApiUserBundle\Entity;

use PhpSolution\StdLib\FrequentField\Interfaces\EmailInterface;
use PhpSolution\StdLib\FrequentField\Interfaces\EnabledInterface;
use PhpSolution\StdLib\FrequentField\Interfaces\IdentifiableInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

/**
 * UserInterface
 */
interface UserInterface extends SymfonyUserInterface, IdentifiableInterface, EmailInterface, EnabledInterface
{
    /**
     * Get the token, that was set to confirm some action
     *
     * @return null|string
     */
    public function getConfirmationToken():? string;

    /**
     * @param null|string $token
     *
     * @return self
     */
    public function setConfirmationToken(string $token = null);

    /**
     * Gets the timestamp when the user requested reset a password
     *
     * @return \DateTime|null
     */
    public function getPasswordRequestedAt():? \DateTime;

    /**
     * @param \DateTime|null $passwordRequestedAt
     *
     * @return self
     */
    public function setPasswordRequestedAt(\DateTime $passwordRequestedAt = null);

    /**
     * @param int $ttl
     *
     * @return bool
     */
    public function isPasswordRequestNonExpired(int $ttl): bool;
}