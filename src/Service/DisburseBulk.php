<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Currency;
use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\MoneywaveResponse;

/**
 * Disburse funds from your Moneywave wallet to a multiple recipient bank accounts.
 *
 * To successfully transfer money from a funded wallet to more than one account at a time, you need to call
 * v1/disburse/queue and supply all the necessary parameters.
 * The ref for the individual disburse transactions must be UNIQUE, and the ref for the whole disburse transaction must
 * be UNIQUE for each disburse transaction.
 *
 *
 * @property string $lock           the password of your wallet
 * @property array  $recipients     the password of your wallet (Use DisburseBulk::addRecipient() to add recipients)
 * @property string $currency       the currency to send money in. One of the Currency::* constants (default: Naira)
 * @property string $senderName     the name of the sender
 * @property string $name           the UNIQUE batch name for this disbursement
 * @property string $ref            a UNIQUE reference code for this transaction
 * @property bool   $instantQueue   should always be set to true
 * @property string $narration      (optional) description of the transaction
 * @property string $disburse_callback_code   (optional) code that you use to verify POSTed responses
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#wallet-to-account-bulk
 */
class DisburseBulk extends AbstractService
{
    /** @var array */
    private $disburseRecipients = [];

    /**
     * DisburseBulk constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->requestData['currency'] = Currency::NAIRA;
        $this->requestData['instantQueue'] = true;
        $this->setRequiredFields('lock', 'recipients', 'currency', 'senderName', 'ref', 'instantQueue', 'name');
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
        return Endpoints::DISBURSE_BULK;
    }

    /**
     * Adds a recipient to the list of recipients for this transaction.
     *
     * @param string $bankCode      the recipient bank code. One of the Banks::* constants
     * @param string $accountNumber the recipient account number
     * @param float  $amount        the amount to be transferred
     * @param string $reference     (optional) the custom unique transaction reference
     * @param string $narration     (optional) a description of the transaction
     *
     * @return DisburseBulk
     */
    public function addRecipient(
        string $bankCode,
        string $accountNumber,
        float $amount,
        string $reference = null,
        string $narration = null
    ): DisburseBulk {
        $recipient = ['bankcode' => $bankCode, 'accountNumber' => $accountNumber, 'amount' => $amount];
        // the new recipient
        if (!empty($reference)) {
            $recipient['ref'] = $reference;
        }
        if (!empty($narration)) {
            $recipient['narration'] = $narration;
        }
        $this->disburseRecipients[] = $recipient;
        // add the recipient
        return $this;
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
        if (empty($this->disburseRecipients)) {
            throw new ValidationException('You need to provide at least 1 recipient');
        }
        $this->requestData['recipients'] = $this->disburseRecipients;

        return parent::send();
    }
}
