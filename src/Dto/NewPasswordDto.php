<?php

namespace PhpSolution\ApiUserBundle\Dto;

/**
 * NewPasswordDto
 */
class NewPasswordDto
{
    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @param string $plainPassword
     */
    public function __construct(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
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
}