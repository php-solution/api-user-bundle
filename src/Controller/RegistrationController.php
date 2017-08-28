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
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request): Response
    {
        $user = $this->get('api_user.process.registration')->register($request->request->all());

        return $this->response($user);
    }

    /**
     * @param Request %token
     *
     * @return Response
     */
    public function confirmAction($token): Response
    {
        $result = $this->get('api_user.process.registration')->confirm($token);

        return $this->response($result);
    }
}