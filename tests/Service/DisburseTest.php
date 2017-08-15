<?php

namespace Emmanix2002\Moneywave\Tests\Service;

use Emmanix2002\Moneywave\Enum\Banks;
use Emmanix2002\Moneywave\Enum\Environment;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\Service\Disburse;
use PHPUnit\Framework\TestCase;

class DisburseTest extends TestCase
{
    /** @var Disburse */
    private $serviceObject;

    public function setUp()
    {
        $moneywave = new Moneywave(ACCESS_TOKEN, API_KEY, SECRET_KEY, Environment::STAGING);
        $this->serviceObject = $moneywave->createDisburseService();
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
        $this->serviceObject->lock = 'wallet password';
        $this->serviceObject->bankcode = Banks::DIAMOND_BANK;
        $this->serviceObject->accountNumber = '0123456789';
        $this->serviceObject->senderName = 'Moneywave Sender';
        $this->serviceObject->amount = 50;
        $this->assertTrue($this->serviceObject->validatePayload());
    }
}
