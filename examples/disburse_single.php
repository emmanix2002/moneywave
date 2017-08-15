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
    $disburse = $mw->createDisburseService();
    $disburse->lock = 'wallet password';
    $disburse->bankcode = Banks::ACCESS_BANK;
    $disburse->accountNumber = '0690000004';
    $disburse->amount = 1.00;
    $disburse->senderName = 'MoneywaveSDK';
    $response = $disburse->send();
    dump($response->getRawResponse());
    dump($response->getData());
    dump($response->getMessage());
} catch (ValidationException $e) {
    dump($e->getMessage());
}
