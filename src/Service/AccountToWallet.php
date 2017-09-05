<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\TransferRecipient;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Transfer funds from a bank account to a Moneywave wallet.
 *
 * The chief premise of this solution is that you can charge any bank account and deposit the funds to your wallet.
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#account-to-wallet-access-bank-only
 */
class AccountToWallet extends AccountTransfer
{
    /**
     * AccountToWallet constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->requestData['recipient'] = TransferRecipient::WALLET;
    }
}
