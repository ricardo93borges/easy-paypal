<?php
namespace paypal\express_checkout;
require_once('api/autoload.php');

//Credentials
$creed = array(
    'username' => 'ricardo_borges26-facilitator_api1.hotmail.com',
    'password' => '53BTEC9TEFQMXUEB',
    'signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AtMJGOkep0UQaULI7-wxg9S7MPtb'
);
//App url
$appUrl = "http://localhost/paypal/express_checkout/";

//Create NvpRequest
$nvp = new api\NvpRequest($creed['username'], $creed['password'], $creed['signature'], true, 'expressCheckout', $appUrl.'checkout.php', $appUrl.'checkout.php');
//Create sellers
$seller = new api\Seller('SALE', 'BRL');
//Add itens to sellers
$item1 = new api\Item('Texugo', 'um texugo', 40.00, 1);
$item2 = new api\Item('Texugo 2', 'outro texugo', 40.00, 1);
$seller->addItem($item1);
$seller->addItem($item2);
//Set request
$nvp->setRequest($seller);

$response = $nvp->send($seller);
var_dump($response);