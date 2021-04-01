<?php

namespace App\Http\Response;

/**
 * Response builder interface for APIs.
 *
 * @package App\Http\Response
 * @since   1.0.0
 */
interface ApiResponseBuilder
{

    /**
     * Get the current response data.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getResponseData(): array;

    /**
     * Get the current set status code.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getStatusCode(): int;

    /**
     * Set the HTTP status code for this response.
     *
     * @param  int  $statusCode
     *
     * @since 1.0.0
     */
    public function setStatusCode(int $statusCode);

    /**
     * Check if a HTTP status code has been set for this response.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasStatusCode(): bool;

    /**
     * Establish an error for this response.
     *
     * @param  int  $statusCode  A valid HTTP header response code.
     * @param  string  $errorCode  A code for the error. Use underscores to
     *                             separate words.
     * @param  string  $errorMsg  A human-readable description of the error.
     *
     * @since 1.0.0
     */
    public function setError(
        int $statusCode,
        string $errorCode,
        string $errorMsg
    );

    /**
     * Check if errors have been set on this response.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasError(): bool;

    /**
     * Set the meta collection en masse. Use this when you already have the
     * full meta data and want to quickly set it on the instance.
     *
     * This will overwrite any existing data that has already been added.
     *
     * @param  array  $data  Key-value pairs are best convention for meta data.
     */
    public function setMeta(array $data);

    /**
     * Add an item to the meta collection.
     *
     * This method will not update an item if it already exists. If you
     * want to update an item, use `updateMeta` instead.
     *
     * @param  string  $key  The key for the item.
     * @param  mixed  $value  The value of the item.
     *
     * @return bool `true` on success, `false` if the key already exists.
     *
     * @since 1.0.0
     */
    public function addMeta(string $key, $value): bool;

    /**
     * Check if an item exists in the meta collection.
     *
     * @param  string  $key  The key of the item.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasMeta(string $key): bool;

    /**
     * Update an item in the meta collection. If the item doesn't exist, it
     * is added.
     *
     * @param  string  $key  The key for the item.
     * @param  mixed  $value  The value of the item.
     *
     * @since 1.0.0
     */
    public function updateMeta(string $key, $value);

    /**
     * Remove an item from the meta collection.
     *
     * @param  string  $key  The key for the item.
     *
     * @since 1.0.0
     */
    public function removeMeta(string $key);

    /**
     * Set the data on the instance as the provided $data param.
     *
     * This will overwrite any existing data that has already been added.
     *
     * @param  array  $data
     */
    public function setData(array $data);

    /**
     * Add an item to the data collection.
     *
     * This method will not update an item if it already exists. If you
     * want to update an item, use `updateData` instead.
     *
     * @param  string  $key  The key for the item.
     * @param  mixed  $value  The value of the item.
     *
     * @return bool `true` on success, `false` if the key already exists.
     *
     * @since 1.0.0
     */
    public function addData(string $key, $value): bool;

    /**
     * Check if an item exists in the data collection.
     *
     * @param  string  $key  The key of the item.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasData(string $key): bool;

    /**
     * Update an item in the data collection. If the item doesn't exist, it
     * is added.
     *
     * @param  string  $key  The key for the item.
     * @param  mixed  $value  The value of the item.
     *
     * @since 1.0.0
     */
    public function updateData(string $key, $value);

    /**
     * Remove an item from the data collection.
     *
     * @param  string  $key  The key for the item.
     *
     * @since 1.0.0
     */
    public function removeData(string $key);

    /**
     * Set the link data on the instance as the provided $data param.
     *
     * This will overwrite any existing data that has already been added.
     *
     * @param  array  $data  Each of the items should be compatible with the
     *                    `addLink()` method.
     *
     * @uses  addLink
     */
    public function setLinks(array $data);

    /**
     * Add an item to the link collection.
     *
     * This method will not update an item if it already exists. If you
     * want to update an item, use `updateLink` instead.
     *
     * @param  string  $name  The name for this link.
     * @param  array  $data  The link data.
     *
     * @return bool `true` on success, `false` if the key already exists.
     *
     * @since 1.0.0
     */
    public function addLink(string $name, array $data): bool;

    /**
     * Check if an item exists in the link collection.
     *
     * @param  string  $name  The name of the link.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasLink(string $name): bool;

    /**
     * Update an item in the link collection. If the item doesn't exist, it
     * is added.
     *
     * @param  string  $name  The name for this link.
     * @param  array  $data  The link data.
     *
     * @since 1.0.0
     */
    public function updateLink(string $name, array $data);

    /**
     * Remove an item from the link collection.
     *
     * @param  string  $name  The name of the link.
     *
     * @since 1.0.0
     */
    public function removeLink(string $name);
}
