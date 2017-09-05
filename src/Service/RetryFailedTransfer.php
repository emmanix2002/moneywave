<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Retry a previously failed disbursement where the card was charged, but the amount was not remitted to the account.
 *
 * It might happen that a transaction fails in a unique way where the card is charged but the disbursement leg to the
 * account fails. To retry these types of transactions, you need to send a POST request to /v1/transfer/disburse/retry,
 * with the id of the successful charge, recipient_account_number and recipient_bank (bankcode).
 *
 *
 * @property string $id                         the id of a successfully charged transfer
 * @property string $recipient_account_number   (optional) disburse destination account number
 * @property string $recipient_bank             (optional) disburse destination bank code. One of the Banks::* constants
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#failed-transaction-retrial
 */
class RetryFailedTransfer extends AbstractService
{
    /**
     * RetryFailedTransfer constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->setRequiredFields('id');
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
        return Endpoints::TRANSFER_RETRY;
    }
}
