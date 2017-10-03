<?php

namespace PhpSolution\ApiUserBundle\Controller;

use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param string $token
     *
     * @return Response
     */
    public function confirmAction(string $token): Response
    {
        try {
            return $this->response($this->get('api_user.process.registration')->confirm($token));
        } catch (NoResultException $ex) {
            throw new NotFoundHttpException(sprintf('User with token "%s" was not found', $token));
        }
    }
}