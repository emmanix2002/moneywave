<?php

namespace Emmanix2002\Moneywave\Tests\Service;

use Emmanix2002\Moneywave\Enum\Environment;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\Service\CardTokenization;
use PHPUnit\Framework\TestCase;

class CardTokenizationTest extends TestCase
{
    /** @var CardTokenization */
    private $serviceObject;

    public function setUp()
    {
        $moneywave = new Moneywave(ACCESS_TOKEN, API_KEY, SECRET_KEY, Environment::STAGING);
        $this->serviceObject = $moneywave->createCardTokenizationService();
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
        $this->serviceObject->card_no = '4267888899993333';
        $this->serviceObject->cvv = '123';
        $this->serviceObject->expiry_year = '2017';
        $this->serviceObject->expiry_month = '01';
        $this->assertTrue($this->serviceObject->validatePayload());
    }
}
