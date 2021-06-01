<?php

namespace App\Constants;

/**
 * A list of basic access levels that can be applied to individual access
 * tokens.
 *
 * @package App\Constants
 * @since 1.0.0
 */
class AccessLevels
{
    /**
     * @since 1.0.0
     */
    const READ_PUBLIC = [
        ApiAbilities::READ_PUBLIC,
    ];

    /**
     * @since 1.0.0
     */
    const READ_ALL = [
        ApiAbilities::READ_PUBLIC,
        ApiAbilities::READ_SENSITIVE,
    ];

    /**
     * @since 1.0.0
     */
    const WRITE = [
        ApiAbilities::READ_PUBLIC,
        ApiAbilities::READ_SENSITIVE,
        ApiAbilities::WRITE,
    ];

    /**
     * @since 1.0.0
     */
    const ADMIN = [
        ApiAbilities::READ_PUBLIC,
        ApiAbilities::READ_SENSITIVE,
        ApiAbilities::WRITE,
        ApiAbilities::ADMINISTRATE,
    ];
}
