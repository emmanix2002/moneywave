<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Currency;
use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Disburse funds from your Moneywave wallet to a single bank account.
 *
 *
 * @property string $lock           the password of your wallet
 * @property float  $amount         the amount to send to the beneficiary
 * @property string $bankcode       the bank code of the bank to send money to. One of the Banks::* constants.
 * @property string $accountNumber  the account number of the recipient
 * @property string $currency       the currency to send money in. One of the Currency::* constants (default: Naira)
 * @property string $senderName     the name of the sender
 * @property string $ref            a UNIQUE reference code for this transaction
 * @property string $narration      some more details about the transaction
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#wallet-to-account-single
 */
class Disburse extends AbstractService
{
    /**
     * Disburse constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->requestData['currency'] = Currency::NAIRA;
        $this->setRequiredFields('lock', 'amount', 'bankcode', 'accountNumber', 'currency', 'senderName');
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
        return Endpoints::DISBURSE;
    }
}
