<?php

namespace Emmanix2002\Moneywave\Tests;

use Emmanix2002\Moneywave\Enum\Environment;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\Service\AccountNumberValidation;
use Emmanix2002\Moneywave\Service\AccountToAccount;
use Emmanix2002\Moneywave\Service\AccountToWallet;
use Emmanix2002\Moneywave\Service\AccountTransfer;
use Emmanix2002\Moneywave\Service\Banks;
use Emmanix2002\Moneywave\Service\CardToBankAccount;
use Emmanix2002\Moneywave\Service\CardTokenization;
use Emmanix2002\Moneywave\Service\CardToWallet;
use Emmanix2002\Moneywave\Service\CardTransfer;
use Emmanix2002\Moneywave\Service\Disburse;
use Emmanix2002\Moneywave\Service\DisburseBulk;
use Emmanix2002\Moneywave\Service\InternetBankingToWallet;
use Emmanix2002\Moneywave\Service\PreviousTransactionQuery;
use Emmanix2002\Moneywave\Service\QueryDisbursement;
use Emmanix2002\Moneywave\Service\RetryFailedTransfer;
use Emmanix2002\Moneywave\Service\TotalChargeToCard;
use Emmanix2002\Moneywave\Service\ValidateAccountTransfer;
use Emmanix2002\Moneywave\Service\ValidateCardTransfer;
use Emmanix2002\Moneywave\Service\VerifyMerchant;
use Emmanix2002\Moneywave\Service\WalletBalance;
use PHPUnit\Framework\TestCase;

class MoneywaveTest extends TestCase
{
    /** @var Moneywave */
    private $moneywave;

    public function setUp()
    {
        $this->moneywave = new Moneywave(ACCESS_TOKEN, API_KEY, SECRET_KEY, Environment::STAGING);
    }

    /**
     * @param $className
     * @param $expected
     *
     * @dataProvider serviceDataProvider
     */
    public function testCreateService($className, $expected)
    {
        $method = 'create'.$className.'Service';
        $serviceObject = $this->moneywave->{$method}();
        $this->assertInstanceOf($expected, $serviceObject);

        return $serviceObject;
    }

    public function testGetApiKey()
    {
        $this->assertEquals(API_KEY, $this->moneywave->getApiKey());
    }

    public function testGetSecretKey()
    {
        $this->assertEquals(SECRET_KEY, $this->moneywave->getSecretKey());
    }

    public function testGetAccessToken()
    {
        $this->assertEquals(ACCESS_TOKEN, $this->moneywave->getAccessToken());
    }

    /**
     * A data provider for the services and their matching classes.
     *
     * @return array
     */
    public function serviceDataProvider()
    {
        return [
            'account validation'        => ['AccountNumberValidation', AccountNumberValidation::class],
            'account to account'        => ['AccountToAccount', AccountToAccount::class],
            'account to wallet'         => ['AccountToWallet', AccountToWallet::class],
            'account transfer'          => ['AccountTransfer', AccountTransfer::class],
            'banks'                     => ['Banks', Banks::class],
            'card to bank'              => ['CardToBankAccount', CardToBankAccount::class],
            'card to wallet'            => ['CardToWallet', CardToWallet::class],
            'card transfer'             => ['CardTransfer', CardTransfer::class],
            'card tokenization'         => ['CardTokenization', CardTokenization::class],
            'disburse'                  => ['Disburse', Disburse::class],
            'disburse bulk'             => ['DisburseBulk', DisburseBulk::class],
            'internet banking'          => ['InternetBankingToWallet', InternetBankingToWallet::class],
            'previous transaction query'=> ['PreviousTransactionQuery', PreviousTransactionQuery::class],
            'query disbursement'        => ['QueryDisbursement', QueryDisbursement::class],
            'retry failed transfer'     => ['RetryFailedTransfer', RetryFailedTransfer::class],
            'total charge to card'      => ['TotalChargeToCard', TotalChargeToCard::class],
            'validate card transfer'    => ['ValidateCardTransfer', ValidateCardTransfer::class],
            'validate account transfer' => ['ValidateAccountTransfer', ValidateAccountTransfer::class],
            'verify merchant'           => ['VerifyMerchant', VerifyMerchant::class],
            'wallet balance'            => ['WalletBalance', WalletBalance::class],
        ];
    }
}
