<?php

use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;

require dirname(__DIR__).'/vendor/autoload.php';
session_start();

try {
    $accessToken = !empty($_SESSION['accessToken']) ? $_SESSION['accessToken'] : null;
    $mw = new Moneywave($accessToken);
    $_SESSION['accessToken'] = $mw->getAccessToken();
    $transferRetry = $mw->createRetryFailedTransferService();
    $transferRetry->id = 'id of transaction';
    $response = $transferRetry->send();
    dump($response->getData());
    dump($response->getMessage());
} catch (ValidationException $e) {
    dump($e->getMessage());
}
