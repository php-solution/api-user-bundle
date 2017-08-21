<?php

namespace PhpSolution\ApiUserBundle\Dto;

/**
 * ChangePasswordDto
 */
class ChangePasswordDto
{
    /**
     * @var null|string
     */
    private $oldPassword;
    /**
     * @var null|string
     */
    private $newPassword;
    /**
     * @var null|string
     */
    private $newPasswordConfirmation;

    /**
     * @return null|string
     */
    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    /**
     * @param string $oldPassword
     *
     * @return self
     */
    public function setOldPassword(string $oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     *
     * @return self
     */
    public function setNewPassword(string $newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getNewPasswordConfirmation(): string
    {
        return $this->newPasswordConfirmation;
    }

    /**
     * @param string $newPasswordConfirmation
     *
     * @return self
     */
    public function setNewPasswordConfirmation(string $newPasswordConfirmation)
    {
        $this->newPasswordConfirmation = $newPasswordConfirmation;

        return $this;
    }
}