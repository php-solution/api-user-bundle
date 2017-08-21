<?php

namespace PhpSolution\ApiUserBundle;

/**
 * UserEvents
 */
final class UserEvents
{
    /**
     * The CHANGE_PASSWORD_COMPLETED event occurs after saving the user in the change password process.
     *
     * @Event("PhpSolution\ApiUserBundle\Event\UserEvent")
     */
    const CHANGE_PASSWORD_COMPLETED = 'php_solution.user_lib.change_password.completed';

    /**
     * The FORGOT_PASSWORD_TOKEN_CREATED event occurs after restore password token was created.
     *
     * @Event("PhpSolution\ApiUserBundle\Event\UserEvent")
     */
    const FORGOT_PASSWORD_TOKEN_CREATED = 'php_solution.user_lib.forgot_password.token_created';

    /**
     * The FORGOT_PASSWORD_COMPLETED event occurs after forgotten password was changed.
     *
     * @Event("PhpSolution\ApiUserBundle\Event\UserEvent")
     */
    const FORGOT_PASSWORD_COMPLETED = 'php_solution.user_lib.forgot_password.completed';

    /**
     * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
     *
     * @Event("PhpSolution\ApiUserBundle\Event\UserEvent")
     */
    const REGISTRATION_COMPLETED = 'php_solution.user_lib.registration.completed';

    /**
     * The REGISTRATION_CONFIRMED event occurs after confirming the account.
     *
     * @Event("PhpSolution\ApiUserBundle\Event\UserEvent")
     */
    const REGISTRATION_CONFIRMED = 'php_solution.registration.confirmed';

    /**
     * The UPDATE_COMPLETED event occurs after user was updated.
     *
     * @Event("PhpSolution\ApiUserBundle\Event\UserEvent")
     */
    const UPDATE_COMPLETED = 'php_solution.update.completed';
}