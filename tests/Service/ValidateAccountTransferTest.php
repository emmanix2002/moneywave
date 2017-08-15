<?php

namespace Emmanix2002\Moneywave\Tests\Service;

use Emmanix2002\Moneywave\Enum\AuthorizationType;
use Emmanix2002\Moneywave\Enum\Environment;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\Service\ValidateAccountTransfer;
use PHPUnit\Framework\TestCase;

class ValidateAccountTransferTest extends TestCase
{
    /** @var ValidateAccountTransfer */
    private $serviceObject;

    public function setUp()
    {
        $moneywave = new Moneywave(ACCESS_TOKEN, API_KEY, SECRET_KEY, Environment::STAGING);
        $this->serviceObject = $moneywave->createValidateAccountTransferService();
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
        $this->serviceObject->authType = AuthorizationType::OTP;
        $this->serviceObject->authValue = '12345';
        $this->serviceObject->transactionRef = 'MW-REFERENCE';
        $this->assertTrue($this->serviceObject->validatePayload());
    }
}
