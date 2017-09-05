<?php

namespace Emmanix2002\Moneywave;

class MoneywaveResponse
{
    /** @var string */
    private $rawResponse;

    /** @var array */
    private $data = [];

    /**
     * MoneywaveResponse constructor.
     *
     * @param string $responseString
     */
    public function __construct(string $responseString)
    {
        $this->rawResponse = $responseString;
        $jsonData = $this->decodeJson($responseString);
        $this->data = $jsonData ?: [];
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Attempts to decode a JSON string to an array; if it fails, it just returns an array with the data index as the
     * string.
     *
     * @param string $jsonString
     *
     * @return array
     */
    private function decodeJson(string $jsonString)
    {
        $json = json_decode($jsonString, true);

        return $json ?: ['data' => $jsonString];
    }

    /**
     * Returns the raw response that was passed into the constructor.
     *
     * @return string
     */
    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    /**
     * Is it a success response?
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        if (empty($this->data)) {
            return false;
        }

        return isset($this->data['status']) && $this->data['status'] === 'success';
    }

    /**
     * The response code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->data['code'] ?? '';
    }

    /**
     * The response message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }

    /**
     * Returns the value of the "data" key in the response if available, else it returns the parsed response.
     *
     * @return array|string
     */
    public function getData()
    {
        return $this->data['data'] ?? $this->data;
    }
}
