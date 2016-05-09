<?php
namespace api;
//use NvpRequest;
require_once('autoload.php');

$creed = array(
    'username' => 'ricardo_borges26-facilitator_api1.hotmail.com',
    'password' => '53BTEC9TEFQMXUEB',
    'signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AtMJGOkep0UQaULI7-wxg9S7MPtb'
);

$nvp = new NvpRequest($creed['username'], $creed['password'], $creed['signature'], true);
$seller = new Seller('SetExpressCheckout', 'BRL');
$item1 = new Item('Texugo', 'um texugo', 40.00, 1);
$item2 = new Item('Texugo 2', 'outro texugo', 40.00, 1);
$seller->addItem($item1);
$seller->addItem($item2);

var_dump($seller);
