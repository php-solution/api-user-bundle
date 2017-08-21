<?php

namespace PhpSolution\ApiUserBundle\Entity;

/**
 * EncodePasswordTrait
 */
trait EncodePasswordTrait
{
    /**
     * @var string
     */
    protected $salt;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * Generate random string - salt, to use for hashing
     */
    public function refreshSalt(): void
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPlainPassword():? string
    {
        return $this->plainPassword;
    }

    /**
     * @param null|string $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword(string $plainPassword = null)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}