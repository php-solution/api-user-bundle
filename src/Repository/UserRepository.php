<?php

namespace PhpSolution\ApiUserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PhpSolution\ApiUserBundle\Entity\UserInterface;

/**
 * @method null|UserInterface find($id, $lockMode = null, $lockVersion = null)
 */
class UserRepository extends EntityRepository
{
    /**
     * @param string $email
     *
     * @return null|object|UserInterface
     */
    public function findOneByEmail(string $email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param string $email
     *
     * @return null|object|UserInterface
     */
    public function findOneEnabledByEmail(string $email)
    {
        return $this->findOneBy(['email' => $email, 'enabled' => true]);
    }

    /**
     * @param string $token
     *
     * @return null|object|UserInterface
     */
    public function findOneByConfirmationToken(string $token)
    {
        return $this->findOneBy(['confirmationToken' => $token]);
    }
}