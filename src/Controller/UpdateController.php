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
     * @param Request $request
     *
     * @return Response
     */
    public function updateAction(Request $request): Response
    {
        $result = $this->get('api_user.process.update')->update($this->getUser(), $request->request->all());

        return $this->response($result);
    }
}