<?php

namespace PhpSolution\ApiUserBundle\Controller;

use PhpSolution\ApiResponseLib\Response\Decorator\ResponseDecoratorTrait;
use PhpSolution\ApiResponseLib\Response\Factory\ResponseFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * AbstractController
 */
abstract class AbstractController extends Controller
{
    use ResponseDecoratorTrait;

    /**
     * @return ResponseFactoryInterface
     */
    protected function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->get('api_response.response_factory');
    }
}