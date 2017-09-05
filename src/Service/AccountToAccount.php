<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\TransferRecipient;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Transfer funds from a bank account to another account.
 *
 * The chief premise of this solution is that you can charge any bank account and deposit the funds to another bank
 * account in one of the supported countries.
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#account-to-account-access-bank-only
 */
class AccountToAccount extends AccountTransfer
{
    /**
     * AccountToAccount constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->requestData['recipient'] = TransferRecipient::ACCOUNT;
        $required = array_merge($this->getRequiredFields(), [
            'recipient_bank',
            'recipient_account_number',
        ]);
        $this->setRequiredFields(...$required);
    }
}
