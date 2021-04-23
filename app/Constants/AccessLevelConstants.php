<?php

namespace App\Constants;

/**
 * A list of basic access levels that can be applied to individual access
 * tokens.
 *
 * @package App\Constants
 * @since 1.0.0
 */
class AccessLevelConstants
{
    /**
     * @since 1.0.0
     */
    const READ = [
        AbilityConstants::READ_PUBLIC,
    ];

    /**
     * @since 1.0.0
     */
    const WRITE = [
        AbilityConstants::READ_PUBLIC,
        AbilityConstants::READ_SENSITIVE,
        AbilityConstants::WRITE,
    ];

    /**
     * @since 1.0.0
     */
    const ADMIN = [
        AbilityConstants::READ_PUBLIC,
        AbilityConstants::READ_SENSITIVE,
        AbilityConstants::WRITE,
        AbilityConstants::ADMINISTRATE,
    ];
}
