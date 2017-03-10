<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Validates a CardToWallet [or CardToAccount] transfer passing in the authentication parameters.
 *
 * This service is used to authorise a transfer of funds from a card [or bank account] to your Moneywave wallet or
 * another bank account.
 * Our Live bank accounts require validation to be charged, this could come as a OTP or ACCOUNT_CREDIT.
 * Response code will be 02 will mean you have to validate the transaction.
 *
 * @package Emmanix2002\Moneywave\Service
 *
 * @property string $transactionRef the flutterChargeReference key value from the success transfer object
 * @property string $authType       the authorization type. One of the AuthorizationType::* constants
 * @property string $authValue      the authorization value. E.g. the OTP token
 */
class ValidateTransfer extends AbstractService
{
    /**
     * ValidateTransfer constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->setRequiredFields('transactionRef', 'authType', 'authValue');
    }
    
    /**
     * Returns the HTTP request method for the service
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        return 'POST';
    }
    
    /**
     * Returns the API request path for the service
     *
     * @return string
     */
    public function getRequestPath(): string
    {
        return Endpoints::TRANSFER_VALIDATE_ACCOUNT;
    }
}
