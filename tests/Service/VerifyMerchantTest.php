<?php

namespace Emmanix2002\Moneywave\Tests\Service;

use Emmanix2002\Moneywave\Enum\Environment;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\Service\VerifyMerchant;
use PHPUnit\Framework\TestCase;

class VerifyMerchantTest extends TestCase
{
    /** @var VerifyMerchant */
    private $serviceObject;

    public function setUp()
    {
        $moneywave = new Moneywave(ACCESS_TOKEN, API_KEY, SECRET_KEY, Environment::STAGING);
        $this->serviceObject = $moneywave->createVerifyMerchantService();
    }

    public function testRequestMethod()
    {
        $this->assertEquals('post', strtolower($this->serviceObject->getRequestMethod()));
    }

    public function testApiKeyIsSet()
    {
        $payload = $this->serviceObject->getPayload();
        $this->assertEquals(API_KEY, $payload['apiKey']);
    }

    public function testSecretKeyIsSet()
    {
        $payload = $this->serviceObject->getPayload();
        $this->assertEquals(SECRET_KEY, $payload['secret']);
    }

    public function testPassValidation()
    {
        $this->serviceObject->amount = 10;
        $this->assertTrue($this->serviceObject->validatePayload());
    }
}
