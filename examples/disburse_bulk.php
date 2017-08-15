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
    $bulkDisbursement = $mw->createDisburseBulkService();
    $bulkDisbursement->lock = 'wallet password';
    $bulkDisbursement->ref = uniqid('txn', true);
    $bulkDisbursement->senderName = 'MoneywaveSDK';
    $bulkDisbursement->addRecipient(Banks::ACCESS_BANK, '0690000004', 1)
                     ->addRecipient(Banks::ACCESS_BANK, '0690000005', 2);
    $response = $bulkDisbursement->send();
    dump($response->getRawResponse());
    dump([
       'Failed transactions'      => $response->failed,
        'Successful transactions' => $response->passed,
    ]);
    dump($response->getData());
    dump($response->getMessage());
} catch (ValidationException $e) {
    dump($e->getMessage());
}
