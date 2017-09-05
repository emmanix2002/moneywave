<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;

/**
 * Get the balances of your Moneywave wallets.
 *
 * It’s inevitable that you’d want to know your wallet balance. To gain access to this information, you need to send a
 * GET request to /v1/wallet.
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#get-wallet-balance
 */
class WalletBalance extends AbstractService
{
    /**
     * Returns the HTTP request method for the service.
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        return 'GET';
    }

    /**
     * Returns the API request path for the service.
     *
     * @return string
     */
    public function getRequestPath(): string
    {
        return Endpoints::WALLET;
    }
}
