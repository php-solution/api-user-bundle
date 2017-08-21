<?php

namespace PhpSolution\ApiUserBundle\Entity;

use PhpSolution\StdLib\FrequentField\Traits\CreatedAtTrait;
use PhpSolution\StdLib\FrequentField\Traits\EmailTrait;
use PhpSolution\StdLib\FrequentField\Traits\EnabledTrait;
use PhpSolution\StdLib\FrequentField\Traits\UpdatedAtTrait;

/**
 * AbstractUser
 */
abstract class AbstractUser implements UserInterface, EncodePasswordInterface, \Serializable
{
    use EmailTrait, EnabledTrait, EncodePasswordTrait, CreatedAtTrait, UpdatedAtTrait;

    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * @var int
     */
    protected $id;
    /**
     * @var string[]
     */
    protected $roles;
    /**
     * @var null|string
     */
    protected $confirmationToken;
    /**
     * @var \DateTime
     */
    protected $passwordRequestedAt;

    /**
     * AbstractUser constructor.
     */
    public function __construct()
    {
        $this->refreshSalt();
        $this->roles = [];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[]
     */
    public function getRoles()
    {
        $roles = $this->roles;
        if (!in_array(static::ROLE_DEFAULT, $roles)) {
            $roles[] = static::ROLE_DEFAULT;
        }

        return $roles;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function addRole(string $role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function removeRole(string $role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @param string[] $roles
     *
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * @param null|string $token
     *
     * @return $this
     */
    public function setConfirmationToken(string $token = null)
    {
        $this->confirmationToken = $token;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken():? string
    {
        return $this->confirmationToken;
    }

    /**
     * @return \DateTime|null
     */
    public function getPasswordRequestedAt():? \DateTime
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param \DateTime|null $passwordRequestedAt
     *
     * @return self
     */
    public function setPasswordRequestedAt(\DateTime $passwordRequestedAt = null)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;

        return $this;
    }

    /**
     * @param int $ttl
     *
     * @return bool
     */
    public function isPasswordRequestNonExpired(int $ttl): bool
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
            $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(
            [
                $this->getId(),
                $this->getEmail(),
                $this->getPassword(),
                $this->getSalt(),
                $this->getRoles(),
                $this->isEnabled(),
            ]
        );
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->salt,
            $this->roles,
            $this->enabled
            )
            = unserialize($serialized);
    }

    /**
     * @return \DateTime|null
     */
    public function getChangedAt():? \DateTime
    {
        return null === $this->getUpdatedAt() ? $this->getCreatedAt() : $this->getUpdatedAt();
    }

    /**
     * Need for lifecycle callbacks
     */
    public function updateCreatedAt(): void
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Need for lifecycle callbacks
     */
    public function updateUpdatedAt(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }
}