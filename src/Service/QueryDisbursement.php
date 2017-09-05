<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Query the details of a wallet to account disbursement transfer.
 *
 * It’s inevitable that you’d eventually need to see your records of previous transactions whether successful or not.
 * To gain access to this information, you need to send a POST request to /v1/disburse/status.
 *
 * @property string $ref    the UNIQUE reference for the disbursement to be queried
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#previous-transactions-api-wallet-to-account
 */
class QueryDisbursement extends AbstractService
{
    /**
     * QueryDisbursement constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->setRequiredFields('ref');
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
        return Endpoints::DISBURSE_STATUS;
    }
}
