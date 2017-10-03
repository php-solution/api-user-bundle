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
    public function findOneByEmail(string $email):? UserInterface
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param string $email
     * @param bool   $enabled
     *
     * @return null|object|UserInterface
     */
    public function findOneByEmailAndEnabled(string $email, bool $enabled):? UserInterface
    {
        return $this->findOneBy(['email' => $email, 'enabled' => $enabled]);
    }

    /**
     * @param string $token
     *
     * @return null|object|UserInterface
     */
    public function findOneByConfirmationToken(string $token):? UserInterface
    {
        return $this->findOneBy(['confirmationToken' => $token]);
    }
}