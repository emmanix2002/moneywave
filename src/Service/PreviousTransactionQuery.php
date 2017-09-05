<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\MoneywaveResponse;

/**
 * Query the details of a card to account transfer.
 *
 * It’s inevitable that you’d eventually need to see your records of previous transactions whether successful or not.
 * To gain access to this information, you need to send a POST request to /v1/transfer/:id, where the id
 * placeholder is the ID of the transaction you’re trying to query.
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#previous-transactions-api-card-to-account
 */
class PreviousTransactionQuery extends AbstractService
{
    /** @var string */
    private $transactionId;

    /**
     * Sets the id of the transaction to be queried.
     *
     * @param string $id the ID of the transaction to be queried
     *
     * @return PreviousTransactionQuery
     */
    public function setTransactionId(string $id): PreviousTransactionQuery
    {
        $this->transactionId = $id;

        return $this;
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
        return Endpoints::TRANSFER.'/'.$this->transactionId;
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
        if (empty($this->transactionId)) {
            throw new ValidationException('You need to set the ID of the transaction to be queried');
        }

        return parent::send();
    }
}
