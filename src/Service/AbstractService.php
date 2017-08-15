<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\MoneywaveResponse;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;

abstract class AbstractService implements ServiceInterface
{
    /** @var Moneywave */
    protected $moneyWave;

    /** @var array */
    protected $requestData = [];

    /** @var array */
    private $requiredFields = [];

    /**
     * AbstractService constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        $this->moneyWave = $moneyWave;
    }

    /**
     * Sets a request field to the specified value.
     *
     * @param string                 $name
     * @param string|int|float|array $value
     */
    public function __set($name, $value)
    {
        if (is_numeric($value) || is_string($value) || is_array($value)) {
            $this->requestData[(string) $name] = $value;
        }
    }

    /**
     * Sets a list of fields as required for this service.
     *
     * @param \string[] ...$fieldNames
     *
     * @return ServiceInterface
     */
    protected function setRequiredFields(string ...$fieldNames): ServiceInterface
    {
        $this->requiredFields = $fieldNames;

        return $this;
    }

    /**
     * Returns the request payload to be sent in the request.
     *
     * @return array
     */
    public function getPayload(): array
    {
        return $this->requestData;
    }

    /**
     * Returns an array of field names that need to be set before the service can pass validation.
     *
     * @return array
     */
    public function getRequiredFields(): array
    {
        return $this->requiredFields;
    }

    /**
     * Checks that all required fields in the payload are set and returns a TRUE or FALSE.
     *
     * @throws ValidationException
     *
     * @return bool
     */
    public function validatePayload(): bool
    {
        if (empty($this->requiredFields)) {
            return true;
        }
        $missingKeys = [];
        foreach ($this->requiredFields as $fieldName) {
            if (!array_key_exists($fieldName, $this->requestData)) {
                $missingKeys[] = $fieldName;
            }
        }
        if (!empty($missingKeys)) {
            throw new ValidationException('Some required fields have not been set: '.implode(', ', $missingKeys));
        }

        return true;
    }

    /**
     * Sends the request to the endpoint.
     * There is the possibility of an unsuccessful request status, that should be watched out for.
     *
     * @throws ValidationException
     *
     * @return MoneywaveResponse
     */
    public function send(): MoneywaveResponse
    {
        $this->validatePayload();
        $headers = ['User-Agent' => 'MoneywaveServiceClient/1.0'];
        if (!empty($this->moneyWave->getAccessToken())) {
            $headers['Authorization'] = $this->moneyWave->getAccessToken();
        }

        try {
            $response = $this->moneyWave->getHttpClient()->request($this->getRequestMethod(), $this->getRequestPath(), [
                RequestOptions::JSON    => $this->getPayload(),
                RequestOptions::HEADERS => $headers,
            ]);

            return new MoneywaveResponse((string) $response->getBody());
        } catch (BadResponseException $e) {
            // in the case of a failure, let's know the status
            $e->getRequest()->getBody()->rewind();
            $size = $e->getRequest()->getBody()->getSize() ?: 1024;
            $bodyParams = json_decode($e->getRequest()->getBody()->read($size), true);
            $serverResponse = (string) $e->getResponse()->getBody();
            $jsonData = json_decode($serverResponse);
            $this->moneyWave->getLogger()->error(
                $e->getResponse()->getStatusCode().': '.$e->getResponse()->getReasonPhrase(),
                [
                    'endpoint' => $e->getRequest()->getUri()->getPath(),
                    'params'   => $bodyParams,
                    'response' => $jsonData ?: $serverResponse,
                ]
            );

            return new MoneywaveResponse((string) $e->getResponse()->getBody());
        } catch (ConnectException $e) {
            $this->moneyWave->getLogger()->error($e->getMessage());

            return new MoneywaveResponse('{"status": "error", "data": "'.$e->getMessage().'"}');
        }
    }

    /**
     * Returns the HTTP request method for the service.
     *
     * @return string
     */
    abstract public function getRequestMethod(): string;

    /**
     * Returns the API request path for the service.
     *
     * @return string
     */
    abstract public function getRequestPath(): string;
}
