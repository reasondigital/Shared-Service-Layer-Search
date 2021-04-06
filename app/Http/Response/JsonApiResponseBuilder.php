<?php

namespace App\Http\Response;

/**
 * Response builder for a JSON API.
 *
 * @package App\Http\Response
 * @since   1.0.0
 */
class JsonApiResponseBuilder implements ApiResponseBuilder
{

    /**
     * @var array
     * @since 1.0.0
     */
    protected array $data = [];

    /**
     * @var array
     * @since 1.0.0
     */
    protected array $meta = [];

    /**
     * @var array
     * @since 1.0.0
     */
    protected array $links = [];

    /**
     * @var array
     * @since 1.0.0
     */
    protected array $responseData = [];

    /**
     * Pull the data together into the appropriate format.
     *
     * @since 1.0.0
     */
    public function build()
    {
        $this->responseData = [
            'data' => $this->data,
            'meta' => $this->meta,
            'links' => $this->links,
        ];
    }

    /**
     * Get the current response data.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getResponseData(): array
    {
        $this->build();

        return $this->responseData;
    }

    /**
     * Get the current set status code.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getStatusCode(): int
    {
        return $this->meta['status_code'];
    }

    /**
     * Set the HTTP status code for this response.
     *
     * @param  int  $statusCode
     *
     * @since 1.0.0
     */
    public function setStatusCode(int $statusCode)
    {
        $this->meta['status_code'] = $statusCode;
    }

    /**
     * Check if a HTTP status code has been set for this response.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasStatusCode(): bool
    {
        return isset($this->meta['status_code']);
    }

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
    ) {
        $this->setStatusCode($statusCode);

        // @todo - This will only allow one error at a time. If we want multiple
        // we'll need to key by the property with the error?
        $this->meta['error'] = [
            'error_type' => $errorCode,
            'error_message' => $errorMsg,
        ];
    }

    /**
     * Check if errors have been set on this response.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasError(): bool
    {
        return isset($this->meta['error']) && !empty($this->meta['error']);
    }

    /**
     * Set the meta collection en masse. Use this when you already have the
     * full meta data and want to quickly set it on the instance.
     *
     * This will overwrite any existing data that has already been added.
     *
     * @param  array  $data  Key-value pairs are best convention for meta data.
     *
     * @uses  addMeta
     *
     * @since 1.0.0
     */
    public function setMeta(array $data)
    {
        $this->meta = [];

        foreach ($data as $key => $item) {
            $this->addMeta($key, $item);
        }
    }

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
    public function addMeta(string $key, $value): bool
    {
        if (isset($this->meta[$key])) {
            return false;
        }

        $this->meta[$key] = $value;

        return true;
    }

    /**
     * Check if an item exists in the meta collection.
     *
     * @param  string  $key  The key of the item.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasMeta(string $key): bool
    {
        return isset($this->meta[$key]);
    }

    /**
     * Update an item in the meta collection. If the item doesn't exist, it
     * is added.
     *
     * @param  string  $key  The key for the item.
     * @param  mixed  $value  The value of the item.
     *
     * @since 1.0.0
     */
    public function updateMeta(string $key, $value)
    {
        $this->meta[$key] = $value;
    }

    /**
     * Remove an item from the meta collection.
     *
     * @param  string  $key  The key for the item.
     *
     * @since 1.0.0
     */
    public function removeMeta(string $key)
    {
        if (isset($this->meta[$key])) {
            unset($this->meta[$key]);
        }
    }

    /**
     * Set the data on the instance as the provided $data param.
     *
     * This will overwrite any existing data that has already been added.
     *
     * @param  array  $data
     *
     * @since 1.0.0
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

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
    public function addData(string $key, $value): bool
    {
        if (isset($this->data[$key])) {
            return false;
        }

        $this->data[$key] = $value;

        return true;
    }

    /**
     * Check if an item exists in the data collection.
     *
     * @param  string  $key  The key of the item.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasData(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Update an item in the data collection. If the item doesn't exist, it
     * is added.
     *
     * @param  string  $key  The key for the item.
     * @param  mixed  $value  The value of the item.
     *
     * @since 1.0.0
     */
    public function updateData(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Remove an item from the data collection.
     *
     * @param  string  $key  The key for the item.
     *
     * @since 1.0.0
     */
    public function removeData(string $key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

    /**
     * Set the link data on the instance as the provided $data param.
     *
     * This will overwrite any existing data that has already been added.
     *
     * @param  array  $data  Each of these items will be passed directly to the
     *                    `addLink()` method.
     *
     * @uses  addLink
     *
     * @since 1.0.0
     */
    public function setLinks(array $data)
    {
        $this->links = [];

        foreach ($data as $key => $item) {
            $this->addLink($key, $item);
        }
    }

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
    public function addLink(string $name, array $data): bool
    {
        if (isset($this->links[$name])) {
            return false;
        }

        $this->links[$name] = $data;

        return true;
    }

    /**
     * Check if an item exists in the link collection.
     *
     * @param  string  $name  The name of the link.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasLink(string $name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * Update an item in the link collection. If the item doesn't exist, it
     * is added.
     *
     * @param  string  $name  The name for this link.
     * @param  array  $data  The link data.
     *
     * @since 1.0.0
     */
    public function updateLink(string $name, array $data)
    {
        $this->links[$name] = $data;
    }

    /**
     * Remove an item from the link collection.
     *
     * @param  string  $name  The name of the link.
     *
     * @since 1.0.0
     */
    public function removeLink(string $name)
    {
        if (isset($this->links[$name])) {
            unset($this->links[$name]);
        }
    }
}
