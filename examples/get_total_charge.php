<?php

use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;

require(dirname(__DIR__).'/vendor/autoload.php');
session_start();

try {
    $accessToken = !empty($_SESSION['accessToken']) ? $_SESSION['accessToken'] : null;
    $mw = new Moneywave($accessToken);
    $_SESSION['accessToken'] = $mw->getAccessToken();
    $chargeToCard = $mw->createTotalChargeToCardService();
    $chargeToCard->amount = 5000.00;
    # the fee is set for you by default - 0
    $response = $chargeToCard->send();
    var_dump($response->getData());
    var_dump($response->getMessage());
} catch (ValidationException $e) {
    var_dump($e->getMessage());
}