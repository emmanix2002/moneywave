<?php

namespace Emmanix2002\Moneywave\Tests\Service;

use Emmanix2002\Moneywave\Enum\Banks;
use Emmanix2002\Moneywave\Enum\Environment;
use Emmanix2002\Moneywave\Enum\PaymentMedium;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\Service\CardToBankAccount;
use PHPUnit\Framework\TestCase;

class CardToBankAccountTest extends TestCase
{
    /** @var CardToBankAccount */
    private $serviceObject;

    public function setUp()
    {
        $moneywave = new Moneywave(ACCESS_TOKEN, API_KEY, SECRET_KEY, Environment::STAGING);
        $this->serviceObject = $moneywave->createCardToBankAccountService();
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

    public function testPassValidation()
    {
        $this->serviceObject->firstname = 'firstname';
        $this->serviceObject->lastname = 'lastname';
        $this->serviceObject->phonenumber = '+2348123456789';
        $this->serviceObject->email = 'username@domain.com';
        $this->serviceObject->recipient_bank = Banks::ASO_SAVINGS_AND_LOANS;
        $this->serviceObject->recipient_account_number = '0123456789';
        $this->serviceObject->card_no = '4267888899993333';
        $this->serviceObject->cvv = '123';
        $this->serviceObject->expiry_year = '2017';
        $this->serviceObject->expiry_month = '01';
        $this->serviceObject->amount = 10;
        $this->serviceObject->redirecturl = 'localhost:8000';
        $this->serviceObject->medium = PaymentMedium::MOBILE;
        $this->assertTrue($this->serviceObject->validatePayload());
    }
}
