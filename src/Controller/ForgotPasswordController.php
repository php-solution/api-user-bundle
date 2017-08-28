<?php

namespace PhpSolution\ApiUserBundle\Controller;

use PhpSolution\ApiUserBundle\Form\Type\ForgotPasswordFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->response($form);
        }
        $result = $this->get('api_user.process.forgot_password')->createToken($form->getData()['email']);

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
        $result = $this->get('api_user.process.forgot_password')->resetPassword($token, $request->request->all());

        return $this->response($result);
    }
}