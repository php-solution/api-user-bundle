<?php

namespace PhpSolution\ApiUserBundle\Util;

use PhpSolution\ApiUserBundle\Entity\AbstractUser;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * UserFactory
 */
class UserFactory
{
    /**
     * @var string
     */
    private $userClass;
    /**
     * @var bool
     */
    private $enabledByDefault;
    /**
     * @var null|TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @param string $userClass
     * @param bool   $enabledByDefault
     */
    public function __construct(string $userClass, bool $enabledByDefault)
    {
        $this->userClass = $userClass;
        $this->enabledByDefault = $enabledByDefault;
    }

    /**
     * @return TokenGeneratorInterface
     */
    public function getTokenGenerator():? TokenGeneratorInterface
    {
        return $this->tokenGenerator;
    }

    /**
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function setTokenGenerator(TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param bool $enable
     *
     * @return AbstractUser
     */
    public function createUser($enable = null): AbstractUser
    {
        $user = $this->createInstance();

        if ($this->enabledByDefault || $enable) {
            $user->setEnabled(true);
        } else {
            $user
                ->setConfirmationToken($this->tokenGenerator->generateToken())
                ->setEnabled(false);
        }

        return $user;
    }

    /**
     * @return AbstractUser
     */
    protected function createInstance(): AbstractUser
    {
        return new $this->userClass;
    }
}