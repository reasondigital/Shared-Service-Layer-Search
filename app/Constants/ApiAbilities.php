<?php

namespace App\Constants;

/**
 * A list of API abilities available in this application. This is primarily to
 * be used with Laravel's Sanctum and its token generation facility.
 *
 * @package App\Constants
 * @since   1.0.0
 */
class ApiAbilities
{
    /**
     * @since 1.0.0
     */
    const READ_PUBLIC = 'read_public';

    /**
     * @since 1.0.0
     */
    const READ_SENSITIVE = 'read_sensitive';

    /**
     * @since 1.0.0
     */
    const WRITE = 'write';

    /**
     * @since 1.0.0
     */
    const ADMINISTRATE = 'administrate';
}
