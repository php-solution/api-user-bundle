<?php

namespace PhpSolution\ApiUserBundle\Controller;

use PhpSolution\StdLib\Exception\NotFoundException;
use PhpSolution\StdLib\Mapper\ObjectMapper;
use PhpSolution\ApiUserBundle\Dto\ResetPasswordDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ForgotPasswordController
 */
class ForgotPasswordController extends AbstractController
{
    /**
     * @var string
     */
    private $resetPasswordDtoClass;

    /**
     * @param string $resetPasswordDtoClass
     */
    public function __construct(string $resetPasswordDtoClass)
    {
        if (!class_exists($resetPasswordDtoClass)) {
            $template = '%s got this class name "%s" in constructor, but this class not exists';
            throw new \RuntimeException(sprintf($template, __CLASS__, $resetPasswordDtoClass));
        }
        $this->resetPasswordDtoClass = $resetPasswordDtoClass;
    }

    /**
     * @return ResetPasswordDto
     */
    public function getResetPasswordDto(): ResetPasswordDto
    {
        return new $this->resetPasswordDtoClass;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function forgotAction(Request $request): Response
    {
        if (empty($email = $request->get('email'))) {
            return $this->errorResponse('Email is empty');
        }
        $user = $this->get('api_user.process.forgot_password')->createToken($email);

        return $this->response($user);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function resetAction(Request $request): Response
    {
        $dto = $this->getResetPasswordDto();
        (new ObjectMapper($dto))->map($request->request->all());

        try {
            $user = $this->get('api_user.process.forgot_password')->resetPassword($dto);
        } catch (NotFoundException $ex) {
            return $this->response($ex);
        }

        return $this->response($user);
    }
}