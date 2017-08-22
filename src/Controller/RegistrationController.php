<?php

namespace PhpSolution\ApiUserBundle\Controller;

use PhpSolution\StdLib\Exception\NotFoundException;
use PhpSolution\StdLib\Mapper\ObjectMapper;
use PhpSolution\ApiUserBundle\Dto\RegistrationDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RegistrationController
 */
class RegistrationController extends AbstractController
{
    /**
     * @var string
     */
    private $registrationDtoClass;

    /**
     * @param string $registrationDtoClass
     */
    public function __construct($registrationDtoClass)
    {
        if (!class_exists($registrationDtoClass)) {
            $template = '%s got this class name "%s" in constructor, but this class not exists';
            throw new \RuntimeException(sprintf($template, __CLASS__, $registrationDtoClass));
        }
        $this->registrationDtoClass = $registrationDtoClass;
    }

    /**
     * @return RegistrationDto
     */
    public function getRegistrationDto(): RegistrationDto
    {
        return new $this->registrationDtoClass;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request): Response
    {
        $dto = $this->getRegistrationDto();
        (new ObjectMapper($dto))->map($request->request->all());

        $user = $this->get('api_user.process.registration')->register($dto);

        return $this->response($user);
    }

    /**
     * @param Request %token
     *
     * @return Response
     */
    public function confirmAction($token): Response
    {
        try {
            $user = $this->get('api_user.process.confirm_registration')->confirm($token);
        } catch (NotFoundException $ex) {
            return $this->response($ex);
        }

        return $this->response($user);
    }
}