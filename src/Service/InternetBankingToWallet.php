<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Banks;
use Emmanix2002\Moneywave\Enum\ChargeAuth;
use Emmanix2002\Moneywave\Enum\ChargeMethod;
use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Enum\TransferRecipient;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\MoneywaveResponse;

/**
 * Allows your to perform account billing via Internet Banking for other supported banks.
 *
 * Only Access Bank allows direct account billing, for a few other supported banks, you need to use this method.
 * On success, you should redirect to the 'authurl' value in the response.
 *
 * Below are the supported banks:
 *
 * Guaranty Trust Bank
 * United Bank for Africa
 * Diamond Bank
 * Zenith Bank
 * First Bank
 *
 *
 * @property string $apiKey             the Moneywave API key (default: Moneywave::getApiKey())
 * @property string $recipient          the cash recipient (default: wallet)
 * @property string $firstname          the card owner's first name
 * @property string $lastname           the card owner's last name
 * @property string $phonenumber        the card owner's phone number in international format (e.g. +23481...)
 * @property string $email              the card owner's email
 * @property float  $amount             the amount to charge to the card
 * @property string $redirecturl        the URL to redirect to after the transaction has been successfully validated
 * @property string $medium             the request medium. One of the PaymentMedium::* constants
 * @property string $sender_bank        the bank code for the account to be billed
 * @property string $charge_with        charge method, should be set to ext_account. One of the ChargeMethod::* constants
 * @property string $card_auth          should be set to INTERNETBANKING
 */
class InternetBankingToWallet extends AbstractService
{
    /**
     * InternetBankingToWallet constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->requestData['apiKey'] = $this->moneyWave->getApiKey();
        $this->requestData['charge_with'] = ChargeMethod::EXT_ACCOUNT;
        $this->requestData['charge_auth'] = ChargeAuth::INTERNET_BANKING;
        $this->requestData['recipient'] = TransferRecipient::WALLET;
        $this->setRequiredFields(
            'apiKey',
            'recipient',
            'firstname',
            'lastname',
            'phonenumber',
            'email',
            'amount',
            'redirecturl',
            'medium',
            'charge_with',
            'charge_auth',
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

    /**
     * {@inheritdoc}
     *
     * @throws \UnexpectedValueException
     */
    public function send(): MoneywaveResponse
    {
        $allowedBanks = array_keys(Banks::getSupportedBanksForInternetBanking());
        // the allowed banks
        if (!in_array($this->requestData['sender_bank'], $allowedBanks)) {
            throw new \UnexpectedValueException(
                'InternetBanking billing is only supported for the following Bank sender_banks: '.
                implode(', ', $allowedBanks)
            );
        }

        return parent::send();
    }
}
