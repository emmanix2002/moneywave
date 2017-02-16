<?php
use Emmanix2002\Moneywave\Moneywave;

require(dirname(__DIR__) . '/vendor/autoload.php');

$mw = new Moneywave();
$bankService = $mw->createBanksService();
$response = $bankService->send();
var_dump($response->getData());