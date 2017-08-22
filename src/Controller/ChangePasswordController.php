<?php

namespace PhpSolution\ApiUserBundle\Controller;

use PhpSolution\StdLib\Mapper\ObjectMapper;
use PhpSolution\ApiUserBundle\Dto\ChangePasswordDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ChangePasswordController
 */
class ChangePasswordController extends AbstractController
{
    /**
     * @var string
     */
    private $changePasswordDtoClass;

    /**
     * @param string $changePasswordDtoClass
     */
    public function __construct(string $changePasswordDtoClass)
    {
        if (!class_exists($changePasswordDtoClass)) {
            $template = '%s got this class name "%s" in constructor, but this class not exists';
            throw new \RuntimeException(sprintf($template, __CLASS__, $changePasswordDtoClass));
        }
        $this->changePasswordDtoClass = $changePasswordDtoClass;
    }

    /**
     * @return ChangePasswordDto
     */
    public function getChangePasswordDto()
    {
        return new $this->changePasswordDtoClass;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function changePasswordAction(Request $request): Response
    {
        $dto = $this->getChangePasswordDto();
        (new ObjectMapper($dto))->map($request->request->all());

        $user = $this->get('api_user.process.change_password')->change($this->getUser(), $dto);

        return $this->response($user);
    }
}