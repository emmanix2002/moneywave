<?php

namespace Emmanix2002\Moneywave\Tests\Service;

use Emmanix2002\Moneywave\Enum\Environment;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;
use Emmanix2002\Moneywave\Service\QueryDisbursement;
use PHPUnit\Framework\TestCase;

class QueryDisbursementTest extends TestCase
{
    /** @var QueryDisbursement */
    private $serviceObject;

    public function setUp()
    {
        $moneywave = new Moneywave(ACCESS_TOKEN, API_KEY, SECRET_KEY, Environment::STAGING);
        $this->serviceObject = $moneywave->createQueryDisbursementService();
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
        $this->serviceObject->ref = 'reference#10';
        $this->assertTrue($this->serviceObject->validatePayload());
    }
}
