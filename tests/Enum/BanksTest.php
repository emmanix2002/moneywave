<?php

namespace Emmanix2002\Moneywave\Tests\Enum;

use Emmanix2002\Moneywave\Enum\Banks;
use PHPUnit\Framework\TestCase;

class BanksTest extends TestCase
{
    public function testGetSupportedBanks()
    {
        $banks = Banks::getSupportedBanks();
        $this->assertNotEmpty($banks);
    }
}
