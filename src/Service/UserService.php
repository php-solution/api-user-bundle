<?php

namespace PhpSolution\ApiUserBundle\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use PhpSolution\Doctrine\Aware\DoctrineAwareTrait;
use PhpSolution\ApiUserBundle\Entity\EncodePasswordInterface;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * UserService
 */
class UserService
{
    use DoctrineAwareTrait;

    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var string
     */
    private $userClass;
    /**
     * @var EncoderFactory
     */
    private $passwordEncoder;

    /**
     * @param ValidatorInterface $validator
     * @param string             $userClass
     * @param EncoderFactory     $passwordEncoder
     */
    public function __construct(ValidatorInterface $validator, string $userClass, EncoderFactory $passwordEncoder)
    {
        $this->validator = $validator;
        $this->userClass = $userClass;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function updateUser(UserInterface $user): UserInterface
    {
        if ($user instanceof EncodePasswordInterface && !empty($plainPassword = $user->getPlainPassword())) {
            $encodedPassword = $this->encodePassword($plainPassword, $user);
            $user->setPassword($encodedPassword);
        }

        $manager = $this->doctrine->getManager();
        $manager->persist($user);
        $manager->flush();
        $user->eraseCredentials();

        return $user;
    }

    /**
     * @return ObjectRepository|UserRepository
     */
    public function getRepository()
    {
        return $this->doctrine->getManager()->getRepository($this->userClass);
    }

    /**
     * @param string        $password
     * @param UserInterface $user
     *
     * @return string
     */
    public function encodePassword(string $password, UserInterface $user): string
    {
        return $this->passwordEncoder->getEncoder($user)->encodePassword($password, $user->getSalt());
    }
}