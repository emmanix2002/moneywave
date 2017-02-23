<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Transfer funds from a card [or bank account] to your Moneywave wallet.
 *
 * The chief premise of this solution is that you can charge any card in the world and deposit the funds to your wallet
 * in that currency.
 *
 * @package Emmanix2002\Moneywave\Service
 *
 * @property string $apiKey             the Moneywave API key (default: Moneywave::getApiKey())
 * @property string $recipient          the cash recipient (default: wallet)
 * @property string $firstname          the card owner's first name
 * @property string $lastname           the card owner's last name
 * @property string $phonenumber        the card owner's phone number in international format (e.g. +23481...)
 * @property string $email              the card owner's email
 * @property string $card_no            the card number
 * @property string $cvv                the card CVV2 number
 * @property string $expiry_year        the card's expiry year
 * @property string $expiry_month       the card's expiry year
 * @property float  $amount             the amount to charge to the card
 * @property float  $fee                the service fee to charge on behalf of the merchant (default: 0)
 * @property string $redirecturl        the URL to redirect to after the transaction has been successfully validated
 * @property string $medium             the request medium. One of the PaymentMedium::* constants
 * @property string $chargeCurrency     (optional) the currency in which card will be charged
 * @property string $disburseCurrency   (optional) the currency in which account will be credited
 * @property string $charge_with        (optional) charge method. One of the ChargeMethod::* constants
 * @property string $card_last4         (optional) card last 4 digits, required in "charge with" ChargeMethod::TOKEN
 * @property string $sender_account_number  (optional) charge source, required in "charge with" ChargeMethod::ACCOUNT
 * @property string $sender_bank        (optional) charge source bank, required in "charge with" ChargeMethod::ACCOUNT
 *
 * @link https://moneywave.flutterwave.com/api#2
 */
class CardToWallet extends AbstractService
{
    /**
     * CardToWallet constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->apiKey = $this->moneyWave->getApiKey();
        $this->recipient = 'wallet';
        $this->fee = 0;
        $this->setRequiredFields(
            'apiKey',
            'recipient',
            'firstname',
            'lastname',
            'phonenumber',
            'email',
            'amount',
            'fee',
            'redirecturl',
            'medium'
        );
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
        return Endpoints::TRANSFER;
    }
}