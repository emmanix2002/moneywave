<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\ChargeMethod;
use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Transfer funds from a bank account to another account or Moneywave wallet.
 *
 * The chief premise of this solution is that you can charge any bank account and deposit the funds to your wallet or
 * another bank account.
 *
 *
 * @property string $apiKey             the Moneywave API key (default: Moneywave::getApiKey())
 * @property string $recipient          the cash recipient (default: wallet)
 * @property string $firstname          the account owner's first name
 * @property string $lastname           the account owner's last name
 * @property string $phonenumber        the account owner's phone number in international format (e.g. +23481...)
 * @property string $email              the account owner's email
 * @property float  $amount             the amount to charge to the account
 * @property float  $fee                the service fee to charge on behalf of the merchant (default: 0)
 * @property string $redirecturl        the URL to redirect to after the transaction has been successfully validated
 * @property string $medium             the request medium. One of the PaymentMedium::* constants
 * @property string $charge_with        charge method. One of the ChargeMethod::* constants
 * @property string $sender_account_number  charge source, required in "charge with" ChargeMethod::ACCOUNT
 * @property string $sender_bank        charge source bank, required in "charge with" ChargeMethod::ACCOUNT
 * @property string $passcode           (optional) Account Security PIN, required in "charge with" ChargeMethod::ACCOUNT
 * @property string $narration          (optional) add some more details to the transaction
 */
class AccountTransfer extends AbstractService
{
    /**
     * AccountTransfer constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->requestData['apiKey'] = $this->moneyWave->getApiKey();
        $this->requestData['fee'] = 0;
        $this->requestData['charge_with'] = ChargeMethod::ACCOUNT;
        $this->setRequiredFields(
            'apiKey',
            'recipient',
            'firstname',
            'lastname',
            'phonenumber',
            'email',
            'charge_with',
            'amount',
            'fee',
            'redirecturl',
            'medium',
            'sender_account_number',
            'sender_bank'
        );
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
        return Endpoints::TRANSFER;
    }
}
