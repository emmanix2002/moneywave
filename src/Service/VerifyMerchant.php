<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Authenticate the merchant against the Moneywave API, and provides an authorization token.
 *
 * Once you have a merchant account, you need to call /v1/merchant/verify to obtain an access token.
 * The token represents you as a merchant and grants you access to every other endpoint.
 * Please note that the tokens expire after 2hrs.
 *
 *
 * @property string $apiKey the Moneywave API key (default: Moneywave::getApiKey())
 * @property string $secret the Moneywave API secret (default: Moneywave::getApiSecret())
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#get-access-token
 */
class VerifyMerchant extends AbstractService
{
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->apiKey = $this->moneyWave->getApiKey();
        $this->secret = $this->moneyWave->getSecretKey();
        $this->setRequiredFields('apiKey', 'secret');
    }

    /**
     * Returns the HTTP request method for the service.
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        return 'POST';
    }

    /**
     * Returns the API request path for the service.
     *
     * @return string
     */
    public function getRequestPath(): string
    {
        return Endpoints::MERCHANT_VERIFY;
    }
}
