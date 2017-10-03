<?php

namespace PhpSolution\ApiUserBundle\Security\User;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * UserNotEnabledException
 */
class UserNotEnabledException extends AuthenticationException
{
}