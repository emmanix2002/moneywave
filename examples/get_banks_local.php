<?php

use Emmanix2002\Moneywave\Enum\Banks;

require dirname(__DIR__).'/vendor/autoload.php';

dump(Banks::getSupportedBanks());
