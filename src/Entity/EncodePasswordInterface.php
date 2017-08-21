<?php

namespace PhpSolution\ApiUserBundle\Entity;

/**
 * EncodePasswordInterface
 */
interface EncodePasswordInterface
{
    /**
     * Generate random string - salt, to use for hashing
     */
    public function refreshSalt(): void;

    /**
     * @return string
     */
    public function getSalt(): string;

    /**
     * @param string $password
     */
    public function setPassword(string $password);

    /**
     * @return null|string
     */
    public function getPlainPassword():? string;

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword);
}