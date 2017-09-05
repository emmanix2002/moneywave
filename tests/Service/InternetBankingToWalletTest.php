<?php

namespace Emmanix2002\Moneywave\Tests\Service;

use Emmanix2002\Moneywave\Enum\Banks;
use Emmanix2002\Moneywave\Enum\Environment;
use Emmanix2002\Moneywave\Enum\PaymentMedium;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\Service\InternetBankingToWallet;
use PHPUnit\Framework\TestCase;

/**
 * Class InternetBankingToWalletTest
 * @package Emmanix2002\Moneywave\Tests\Service
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#pay-with-internet-banking
 */
class InternetBankingToWalletTest extends TestCase
{
    /** @var InternetBankingToWallet */
    private $serviceObject;

    public function setUp()
    {
        $moneywave = new Moneywave(ACCESS_TOKEN, API_KEY, SECRET_KEY, Environment::STAGING);
        $this->serviceObject = $moneywave->createInternetBankingToWalletService();
    }

    public function testRequestMethod()
    {
        $this->assertEquals('post', strtolower($this->serviceObject->getRequestMethod()));
    }

    public function testFailsValidation()
    {
        $this->expectException(ValidationException::class);
        $this->serviceObject->validatePayload();
    }

    public function testUnsupportedBankValidation()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->serviceObject->firstname = 'firstname';
        $this->serviceObject->lastname = 'lastname';
        $this->serviceObject->phonenumber = '+2348123456789';
        $this->serviceObject->email = 'username@domain.com';
        $this->serviceObject->amount = 10;
        $this->serviceObject->redirect_url = 'localhost:8000';
        $this->serviceObject->medium = PaymentMedium::MOBILE;
        $this->serviceObject->sender_bank = Banks::GTBANK_MOBILE_MONEY;
        $this->serviceObject->send();
    }

    public function testPassValidation()
    {
        $this->serviceObject->firstname = 'firstname';
        $this->serviceObject->lastname = 'lastname';
        $this->serviceObject->phonenumber = '+2348123456789';
        $this->serviceObject->email = 'username@domain.com';
        $this->serviceObject->amount = 10;
        $this->serviceObject->redirecturl = 'localhost:8000';
        $this->serviceObject->medium = PaymentMedium::MOBILE;
        $this->serviceObject->sender_bank = Banks::GTBANK;
        $this->assertTrue($this->serviceObject->validatePayload());
    }
}
