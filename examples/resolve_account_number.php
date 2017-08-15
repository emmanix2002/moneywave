<?php

use Emmanix2002\Moneywave\Enum\Banks;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;

require dirname(__DIR__).'/vendor/autoload.php';
session_start();

try {
    $accessToken = !empty($_SESSION['accessToken']) ? $_SESSION['accessToken'] : null;
    $mw = new Moneywave($accessToken);
    $_SESSION['accessToken'] = $mw->getAccessToken();
    $accountValidation = $mw->createAccountNumberValidationService();
    $accountValidation->bank_code = Banks::ACCESS_BANK;
    $accountValidation->account_number = '0690000004';
    $response = $accountValidation->send();
    dump($response->getData());
    dump($response->getMessage());
} catch (ValidationException $e) {
    dump($e->getMessage());
}
