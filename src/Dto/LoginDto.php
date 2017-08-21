<?php

namespace PhpSolution\ApiUserBundle\Dto;

use PhpSolution\StdLib\FrequentField\Traits\EmailTrait;

/**
 * LoginDto
 */
class LoginDto
{
    use EmailTrait;

    /**
     * @var null|string
     */
    private $password;

    /**
     * @return null|string
     */
    public function getPassword():? string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }
}