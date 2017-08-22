<?php

namespace PhpSolution\ApiUserBundle\Controller;

use PhpSolution\ApiUserBundle\Dto\UpdateDto;
use PhpSolution\StdLib\Mapper\ObjectMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * UpdateController
 */
class UpdateController extends AbstractController
{
    /**
     * @var string
     */
    private $updateDtoClass;

    /**
     * @param string $updateDtoClass
     */
    public function __construct($updateDtoClass)
    {
        if (!class_exists($updateDtoClass)) {
            $template = '%s got this class name "%s" in constructor, but this class not exists';
            throw new \RuntimeException(sprintf($template, __CLASS__, $updateDtoClass));
        }
        $this->updateDtoClass = $updateDtoClass;
    }

    /**
     * @return UpdateDto
     */
    public function getUpdateDto()
    {
        return new $this->updateDtoClass;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function updateAction(Request $request): Response
    {
        $dto = $this->getUpdateDto();
        (new ObjectMapper($dto))->map($request->request->all());

        $user = $this->get('api_user.process.update')->update($this->getUser(), $dto);

        return $this->response($user);
    }
}