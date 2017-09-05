<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Validate the details of a bank account by getting the account name.
 *
 * Before doing a transfer or disbursement to a bank account, it’s good practice to validate the details of the
 * destination bank account. Our API allows you to do just that by verifying the account number and returning the
 * account name. To do that, you need to send a POST request to /v1/resolve/account, with the account number and bank
 * code in the body of the request.
 *
 *
 * @property string $account_number the account number to be resolved
 * @property string $bank_code      the bank code for the account number to be resolved
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#account-number-validation
 */
class AccountNumberValidation extends AbstractService
{
    /**
     * AccountNumberValidation constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->setRequiredFields('account_number', 'bank_code');
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
        return Endpoints::RESOLVE_ACCOUNT;
    }
}
