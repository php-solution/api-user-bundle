<?php

namespace PhpSolution\ApiUserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ChangePasswordController
 */
class ChangePasswordController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function changePasswordAction(Request $request): Response
    {
        $result = $this->get('api_user.process.change_password')->change($this->getUser(), $request->request->all());

        return $this->response($result);
    }
}