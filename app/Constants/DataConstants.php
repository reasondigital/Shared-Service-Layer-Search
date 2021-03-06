<?php

namespace App\Constants;

/**
 * A place to hardcode application values related to API data and data
 * processing.
 *
 * @package App\Constants
 * @since 1.0.0
 */
class DataConstants
{
    /**
     * @since 1.0.0
     */
    const SCHEMA_CONTEXT = 'https://schema.org';

    /**
     * @since 1.0.0
     */
    const POSTAL_CODE_REGEX_UK = '/^(([A-Z][0-9]{1,2})|(([A-Z][A-HJ-Y][0-9]{1,2})|(([A-Z][0-9][A-Z])|([A-Z][A-HJ-Y][0-9]?[A-Z])))) [0-9][A-Z]{2}$/';

    /**
     * @since 1.0.0
     */
    const ELASTIC_DATETIME_FORMAT = 'Y-m-d\TH:i:s\Z';

    /**
     * @since 1.0.0
     */
    const API_ARTICLE_DATE_PUBLISHED_FORMAT = 'Y-m-d H:i:s';
}
