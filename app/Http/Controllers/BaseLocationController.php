<?php

namespace App\Http\Controllers;

use App\Geo\Address;

/**
 *
 *
 * @since 1.0.0
 *@package App\Http\Controllers
 */
abstract class BaseLocationController extends SearchController
{
    /**
     * @since 1.0.0
     */
    const ERROR_MSG_NOT_FOUND = 'No location was found with the given ID';

    /**
     * @param  Address  $address
     *
     * @return array
     * @since 1.0.0
     */
    protected function getCoords(Address $address): array
    {
        if ($address->empty()) {
            return [];
        } else {
            return [
                'lat' => $address->latitude,
                'lon' => $address->longitude,
            ];
        }
    }
}
