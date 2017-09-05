<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Validate a transfer from a Verve card.
 *
 * Verve Cards will be charged using PIN, the bank determines if they want an extra level of validation; if they do,
 * response code will be 02, and that means you have to validate the transaction.
 *
 *
 * @property string $transactionRef the flutterChargeReference key value from the success transfer object
 * @property string $otp            the authorization value. E.g. the OTP token
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#cards-to-account-wallet-validation
 */
class ValidateCardTransfer extends AbstractService
{
    /**
     * ValidateCardTransfer constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->setRequiredFields('transactionRef', 'otp');
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
        return Endpoints::TRANSFER_VALIDATE_CARD;
    }
}
