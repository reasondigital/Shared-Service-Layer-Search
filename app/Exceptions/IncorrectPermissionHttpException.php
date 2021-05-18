<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Thrown when an access token does not have the ability required to perform
 * the action that it is attempting.
 *
 * @package App\Exceptions
 * @since   1.0.0
 */
class IncorrectPermissionHttpException extends HttpException
{
}
