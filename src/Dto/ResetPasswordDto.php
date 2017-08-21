<?php

namespace PhpSolution\ApiUserBundle\Dto;

/**
 * ResetPasswordDto
 */
class ResetPasswordDto
{
    /**
     * @var null|string
     */
    private $token;

    /**
     * @var null|string
     */
    private $plainPassword;

    /**
     * @return null|string
     */
    public function getToken():? string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return self
     */
    public function setToken(string $token)
    {
        $this->token = $token;

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
}