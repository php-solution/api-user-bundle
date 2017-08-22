<?php
namespace PhpSolution\ApiUserBundle\Dto;

/**
 * RegistrationDto
 */
class RegistrationDto
{
    /**
     * @var null|string
     */
    private $email;

    /**
     * @var null|string
     */
    private $plainPassword;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @return null|string
     */
    public function getEmail():? string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

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
     * @param string $plainPassword
     *
     * @return self
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }
}