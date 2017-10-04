<?php

namespace PhpSolution\ApiUserBundle\Controller;

use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ForgotPasswordController
 */
class ForgotPasswordController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function forgotAction(Request $request): Response
    {
        try {
            $result = $this->get('api_user.process.forgot_password')->createToken($request->request->all());
        } catch (NoResultException $ex) {
            throw new NotFoundHttpException(sprintf('User with email "%s" could not be found', $request->get('email')));
        }

        return $this->response($result);
    }

    /**
     * @param Request $request
     * @param string  $token
     *
     * @return Response
     */
    public function resetAction(Request $request, string $token): Response
    {
        try {
            $result = $this->get('api_user.process.forgot_password')->resetPassword($token, $request->request->all());
        } catch (NoResultException $ex) {
            throw new NotFoundHttpException(sprintf('User with token "%s" could not be found', $token));
        }

        return $this->response($result);
    }
}