<?php

use Emmanix2002\Moneywave\Enum\Banks;
use Emmanix2002\Moneywave\Enum\PaymentMedium;
use Emmanix2002\Moneywave\Exception\ValidationException;
use Emmanix2002\Moneywave\Moneywave;

require dirname(__DIR__).'/vendor/autoload.php';
session_start();

try {
    $accessToken = !empty($_SESSION['accessToken']) ? $_SESSION['accessToken'] : null;
    $mw = new Moneywave($accessToken);
    $_SESSION['accessToken'] = $mw->getAccessToken();
    $cardToAccount = $mw->createCardToBankAccountService();
    $cardToAccount->firstname = 'Firstname';
    $cardToAccount->lastname = 'Surname';
    $cardToAccount->phonenumber = '+2348123456789';
    $cardToAccount->email = 'username@domain.com';
    $cardToAccount->recipient_bank = Banks::ACCESS_BANK;
    $cardToAccount->recipient_account_number = '0690000004';
    $cardToAccount->card_no = '1234567890123456';
    $cardToAccount->cvv = '123';
    $cardToAccount->expiry_year = '2018';
    $cardToAccount->expiry_month = '06';
    $cardToAccount->amount = 1.00;
    $cardToAccount->fee = 0;
    $cardToAccount->redirecturl = 'localhost:8000/transfer_card_to_bank.php';
    $cardToAccount->medium = PaymentMedium::WEB;
    $response = $cardToAccount->send();
    dump($response->getData());
    dump($response->getMessage());
} catch (ValidationException $e) {
    dump($e->getMessage());
}
