<?php

namespace PhpSolution\ApiUserBundle\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
use PhpSolution\Doctrine\Aware\DoctrineAwareTrait;
use PhpSolution\StdLib\Mapper\ObjectMapper;
use PhpSolution\ApiUserBundle\Entity\EncodePasswordInterface;
use PhpSolution\ApiUserBundle\Entity\UserInterface;
use PhpSolution\ApiUserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
     * @param null|object   $dto
     * @param array    $validationGroups
     *
     * @return ConstraintViolationListInterface|UserInterface
     */
    public function updateUser(UserInterface $user, $dto = null, $validationGroups = [])
    {
        $manager = $this->doctrine->getManager();

        if ($dto !== null) {
            (new ObjectMapper($user))->map($dto);

            $validationGroups[] = 'all';
            $violations = $this->validator->validate($user, null, $validationGroups);
            if ($violations->count() > 0) {
                try {
                    $manager->refresh($user);
                } catch (ORMInvalidArgumentException $ex) {}

                return $violations;
            }
        }

        if ($user instanceof EncodePasswordInterface && !empty($plainPassword = $user->getPlainPassword())) {
            $encodedPassword = $this->encodePassword($plainPassword, $user);
            $user->setPassword($encodedPassword);
        }

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