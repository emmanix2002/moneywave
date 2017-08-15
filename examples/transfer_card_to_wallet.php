<?php

use Emmanix2002\Moneywave\Enum\PaymentMedium;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;

require dirname(__DIR__).'/vendor/autoload.php';
session_start();

try {
    $accessToken = !empty($_SESSION['accessToken']) ? $_SESSION['accessToken'] : null;
    $mw = new Moneywave($accessToken);
    $_SESSION['accessToken'] = $mw->getAccessToken();
    $cardToWallet = $mw->createCardToWalletService();
    $cardToWallet->firstname = 'Firstname';
    $cardToWallet->lastname = 'Surname';
    $cardToWallet->phonenumber = '+2348123456789';
    $cardToWallet->email = 'username@domain.com';
    $cardToWallet->card_no = '1234567890123456';
    $cardToWallet->cvv = '123';
    $cardToWallet->expiry_year = '2018';
    $cardToWallet->expiry_month = '06';
    $cardToWallet->amount = 1.00;
    $cardToWallet->fee = 0;
    $cardToWallet->redirecturl = 'localhost:8000/transfer_card_to_bank.php';
    $cardToWallet->medium = PaymentMedium::WEB;
    $response = $cardToWallet->send();
    dump($response->getData());
    dump($response->getMessage());
} catch (ValidationException $e) {
    dump($e->getMessage());
}
