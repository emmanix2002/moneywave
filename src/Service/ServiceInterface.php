<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\MoneywaveResponse;
use GuzzleHttp\Exception\BadResponseException;

interface ServiceInterface
{
    /**
     * Returns the request payload to be sent in the request.
     *
     * @return array
     */
    public function getPayload(): array;

    /**
     * Checks that all required fields in the payload are set and returns a TRUE or FALSE.
     *
     * @throws ValidationException
     *
     * @return bool
     */
    public function validatePayload(): bool;

    /**
     * Returns an array of field names that need to be set before the service can pass validation.
     *
     * @return array
     */
    public function getRequiredFields(): array;

    /**
     * Sends the request to the endpoint.
     * There is the possibility of an unsuccessful request status, that should be watched out for.
     *
     * @throws BadResponseException
     *
     * @return MoneywaveResponse
     */
    public function send(): MoneywaveResponse;
}
